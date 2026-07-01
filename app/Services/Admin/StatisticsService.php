<?php

namespace App\Services\Admin;

use App\DTOs\Admin\DateRangeFilterData;
use App\Repositories\Admin\MetricsRepository;

class StatisticsService
{
    public function __construct(private readonly MetricsRepository $metricsRepository) {}

    public function chart(string $metric, array $filters = []): array
    {
        $range = DateRangeFilterData::fromArray($filters);

        return match ($metric) {
            'aspirantes_por_mes' => $this->metricsRepository->monthlyAspirantes($range),
            'inscripciones_por_mes' => $this->metricsRepository->monthlyInscripciones($range),
            'ingresos_por_mes' => $this->metricsRepository->monthlyIngresos($range),
            'pagos_por_estado' => $this->metricsRepository->pagosPorEstado($range),
            'aspirantes_por_curso' => $this->metricsRepository->aspirantesPorCurso($range),
            'cursos_mayor_demanda' => $this->metricsRepository->cursosMayorDemanda($range),
            'crecimiento_inscripciones' => $this->metricsRepository->crecimientoInscripciones($range),
            'comparativa_ingresos' => $this->metricsRepository->comparativaIngresos($range),
            'distribucion_estados_inscripcion' => $this->metricsRepository->distribucionEstadosInscripcion($range),
            'top_cursos_mas_inscritos' => $this->metricsRepository->topCursosMasInscritos($range),
            default => [],
        };
    }

    public function payload(array $filters = []): array
    {
        $range = DateRangeFilterData::fromArray($filters);

        return [
            'range' => ['fecha_desde' => $range->from, 'fecha_hasta' => $range->to],
            'aspirantes_por_mes' => $this->metricsRepository->monthlyAspirantes($range),
            'inscripciones_por_mes' => $this->metricsRepository->monthlyInscripciones($range),
            'ingresos_por_mes' => $this->metricsRepository->monthlyIngresos($range),
            'pagos_por_estado' => $this->metricsRepository->pagosPorEstado($range),
            'aspirantes_por_curso' => $this->metricsRepository->aspirantesPorCurso($range),
            'cursos_mayor_demanda' => $this->metricsRepository->cursosMayorDemanda($range),
            'crecimiento_inscripciones' => $this->metricsRepository->crecimientoInscripciones($range),
            'comparativa_ingresos' => $this->metricsRepository->comparativaIngresos($range),
            'distribucion_estados_inscripcion' => $this->metricsRepository->distribucionEstadosInscripcion($range),
            'top_cursos_mas_inscritos' => $this->metricsRepository->topCursosMasInscritos($range),
        ];
    }
}
