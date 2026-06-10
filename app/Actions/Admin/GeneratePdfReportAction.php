<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Services\Admin\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePdfReportAction
{
    public function __construct(private readonly ReportService $reportService) {}

    public function execute(array $filters, User $user)
    {
        return Pdf::loadView('reports.inscripciones_pdf', $this->reportService->pdfPayload($filters, $user));
    }
}
