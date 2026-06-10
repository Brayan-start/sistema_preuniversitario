<?php

namespace App\Services\Admin;

use App\DTOs\Admin\DateRangeFilterData;
use App\Repositories\Admin\MetricsRepository;

class DashboardService
{
    public function __construct(private readonly MetricsRepository $metricsRepository) {}

    public function build(array $filters = []): array
    {
        $range = DateRangeFilterData::fromArray($filters);

        return [
            'kpis' => $this->metricsRepository->totals(),
            'ultimas_inscripciones' => $this->metricsRepository->latestInscripciones(),
            'ultimos_aspirantes' => $this->metricsRepository->latestAspirantes(),
            'estadisticas' => [
                'aspirantes_por_mes' => $this->metricsRepository->monthlyAspirantes($range),
                'inscripciones_por_mes' => $this->metricsRepository->monthlyInscripciones($range),
                'ingresos_por_mes' => $this->metricsRepository->monthlyIngresos($range),
                'pagos_por_estado' => $this->metricsRepository->pagosPorEstado($range),
                'aspirantes_por_curso' => $this->metricsRepository->aspirantesPorCurso($range),
            ],
            'filtros' => [
                'fecha_desde' => $range->from,
                'fecha_hasta' => $range->to,
            ],
        ];
    }
}
