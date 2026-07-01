<?php

namespace App\Repositories\Admin;

use Illuminate\Database\Eloquent\Builder;
use App\DTOs\Admin\DateRangeFilterData;
use App\Models\Aspirante;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;

class MetricsRepository
{
    public function totals(): array
    {
        return [
            'total_aspirantes' => Aspirante::count(),
            'total_inscripciones' => Inscripcion::has('aspirante')->count(),
            'total_cursos_activos' => Curso::where('is_active', true)->count(),
            'total_pagos_realizados' => Pago::whereHas('inscripcion.aspirante')->where('estado', 'aprobado')->count(),
            'total_pagos_pendientes' => Pago::whereHas('inscripcion.aspirante')->whereIn('estado', ['pendiente', 'en_revision'])->count(),
            'ingresos_acumulados' => (float) Pago::whereHas('inscripcion.aspirante')->where('estado', 'aprobado')->sum('monto'),
            'ingresos_mes_actual' => (float) Pago::whereHas('inscripcion.aspirante')->where('estado', 'aprobado')
                ->whereMonth('fecha_pago', now()->month)
                ->whereYear('fecha_pago', now()->year)
                ->sum('monto'),
            'nuevos_aspirantes_mes' => Aspirante::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'porcentaje_pagos_completados' => $this->percentageCompletedPayments(),
            'promedio_inscripciones_por_curso' => $this->averageInscripcionesByCurso(),
        ];
    }

    public function monthlyAspirantes(DateRangeFilterData $range): array
    {
        return $this->buildMonthlyCountSeries(Aspirante::query(), $range, 'created_at');
    }

    public function monthlyInscripciones(DateRangeFilterData $range): array
    {
        return $this->buildMonthlyCountSeries(Inscripcion::has('aspirante'), $range, 'created_at');
    }

    public function monthlyIngresos(DateRangeFilterData $range): array
    {
        return $this->buildMonthlySumSeries(Pago::whereHas('inscripcion.aspirante')->where('estado', 'aprobado'), $range, 'fecha_pago', 'monto');
    }

    public function pagosPorEstado(DateRangeFilterData $range): array
    {
        $query = Pago::whereHas('inscripcion.aspirante');
        $this->applyRange($query, $range, 'created_at');

        return $query->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->orderBy('estado')
            ->get()
            ->map(fn($item) => ['label' => $item->estado, 'value' => (int) $item->total])
            ->values()
            ->all();
    }

    public function aspirantesPorCurso(DateRangeFilterData $range): array
    {
        $query = Inscripcion::has('aspirante')->with('curso');
        $this->applyRange($query, $range, 'created_at');

        return $query->select('curso_id', DB::raw('count(*) as total'))
            ->groupBy('curso_id')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'label' => optional($item->curso)->nombre_curso ?? 'Sin curso',
                'value' => (int) $item->total,
            ])
            ->values()
            ->all();
    }

    public function cursosMayorDemanda(DateRangeFilterData $range): array
    {
        return $this->aspirantesPorCurso($range);
    }

    public function crecimientoInscripciones(DateRangeFilterData $range): array
    {
        return $this->monthlyInscripciones($range);
    }

    public function comparativaIngresos(DateRangeFilterData $range): array
    {
        return $this->monthlyIngresos($range);
    }

    public function distribucionEstadosInscripcion(DateRangeFilterData $range): array
    {
        $query = Inscripcion::has('aspirante');
        $this->applyRange($query, $range, 'created_at');

        return $query->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->orderBy('estado')
            ->get()
            ->map(fn($item) => ['label' => $item->estado, 'value' => (int) $item->total])
            ->values()
            ->all();
    }

    public function topCursosMasInscritos(DateRangeFilterData $range): array
    {
        return $this->aspirantesPorCurso($range);
    }

    public function latestInscripciones(int $limit = 5)
    {
        return Inscripcion::has('aspirante')->with(['aspirante', 'curso'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function latestAspirantes(int $limit = 5)
    {
        return Aspirante::latest()->limit($limit)->get();
    }

    private function percentageCompletedPayments(): float
    {
        $total = Pago::whereHas('inscripcion.aspirante')->count();

        if ($total === 0) {
            return 0.0;
        }

        return round((Pago::whereHas('inscripcion.aspirante')->where('estado', 'aprobado')->count() / $total) * 100, 2);
    }

    private function averageInscripcionesByCurso(): float
    {
        $activeCourses = Curso::count();

        if ($activeCourses === 0) {
            return 0.0;
        }

        return round(Inscripcion::has('aspirante')->count() / $activeCourses, 2);
    }

    private function buildMonthlyCountSeries(Builder $query, DateRangeFilterData $range, string $dateColumn): array
    {
        $this->applyRange($query, $range, $dateColumn);
        $periodExpression = $this->monthlyPeriodExpression($dateColumn);

        return $query->selectRaw("{$periodExpression} as period, count(*) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn($item) => ['label' => $item->period, 'value' => (int) $item->total])
            ->values()
            ->all();
    }

    private function buildMonthlySumSeries(Builder $query, DateRangeFilterData $range, string $dateColumn, string $sumColumn): array
    {
        $this->applyRange($query, $range, $dateColumn);
        $periodExpression = $this->monthlyPeriodExpression($dateColumn);

        return $query->selectRaw("{$periodExpression} as period, COALESCE(SUM({$sumColumn}), 0) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn($item) => ['label' => $item->period, 'value' => (float) $item->total])
            ->values()
            ->all();
    }

    private function monthlyPeriodExpression(string $dateColumn): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', {$dateColumn})",
            'pgsql' => "TO_CHAR({$dateColumn}, 'YYYY-MM')",
            default => "DATE_FORMAT({$dateColumn}, '%Y-%m')",
        };
    }

    private function applyRange(Builder $query, DateRangeFilterData $range, string $dateColumn): void
    {
        if ($range->startDate() && $range->endDate()) {
            $query->whereBetween($dateColumn, [$range->startDate(), $range->endDate()]);

            return;
        }

        if ($range->startDate()) {
            $query->where($dateColumn, '>=', $range->startDate());
        }

        if ($range->endDate()) {
            $query->where($dateColumn, '<=', $range->endDate());
        }
    }
}
