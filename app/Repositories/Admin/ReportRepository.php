<?php

namespace App\Repositories\Admin;

use Illuminate\Database\Eloquent\Builder;
use App\DTOs\Admin\DateRangeFilterData;
use App\Models\Aspirante;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Pago;

class ReportRepository
{
    public function defaultFilters(): array
    {
        return [
            'fecha_desde' => now()->startOfMonth()->toDateString(),
            'fecha_hasta' => now()->endOfMonth()->toDateString(),
        ];
    }

    public function aspirantes(array $filters = [])
    {
        return Aspirante::with('user')->latest()->get();
    }

    public function inscripciones(array $filters = [])
    {
        $query = Inscripcion::with(['aspirante', 'curso', 'pago'])->latest();
        $this->applyRange($query, DateRangeFilterData::fromArray($filters));

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['curso_id'])) {
            $query->where('curso_id', $filters['curso_id']);
        }

        return $query->get();
    }

    public function pagos(array $filters = [])
    {
        $query = Pago::with(['inscripcion.aspirante', 'inscripcion.curso'])->latest();
        $this->applyRange($query, DateRangeFilterData::fromArray($filters), 'fecha_pago');

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        return $query->get();
    }

    public function cursos(array $filters = [])
    {
        return Curso::latest()->get();
    }

    private function applyRange(Builder $query, DateRangeFilterData $range, string $column = 'created_at'): void
    {
        if ($range->startDate() && $range->endDate()) {
            $query->whereBetween($column, [$range->startDate(), $range->endDate()]);

            return;
        }

        if ($range->startDate()) {
            $query->where($column, '>=', $range->startDate());
        }

        if ($range->endDate()) {
            $query->where($column, '<=', $range->endDate());
        }
    }
}
