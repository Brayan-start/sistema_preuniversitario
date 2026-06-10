<?php

namespace App\Services\Admin;

use App\Models\User;
use App\DTOs\Admin\DateRangeFilterData;
use App\Repositories\Admin\MetricsRepository;
use App\Repositories\Admin\ReportRepository;

class ReportService
{
    public function __construct(
        private readonly ReportRepository $reportRepository,
        private readonly MetricsRepository $metricsRepository,
        private readonly InterpretationService $interpretationService,
    ) {}

    public function defaultFilters(): array
    {
        return $this->reportRepository->defaultFilters();
    }

    public function inscripciones(array $filters = [])
    {
        return $this->reportRepository->inscripciones($filters);
    }

    public function pagos(array $filters = [])
    {
        return $this->reportRepository->pagos($filters);
    }

    public function pdfPayload(array $filters, User $user): array
    {
        $range = DateRangeFilterData::fromArray($filters);
        $metrics = $this->metricsRepository->totals();

        return [
            'generatedAt' => now(),
            'generatedBy' => $user,
            'filters' => $range,
            'inscripciones' => $this->reportRepository->inscripciones($filters),
            'pagos' => $this->reportRepository->pagos($filters),
            'cursos' => $this->reportRepository->cursos($filters),
            'metrics' => $metrics,
            'interpretations' => $this->interpretationService->build($filters, $metrics),
        ];
    }
}
