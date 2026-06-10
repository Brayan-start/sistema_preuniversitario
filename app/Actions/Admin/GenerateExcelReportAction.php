<?php

namespace App\Actions\Admin;

use App\Exports\AdminReportExport;
use App\Services\Admin\ReportService;
use Maatwebsite\Excel\Facades\Excel;

class GenerateExcelReportAction
{
    public function __construct(private readonly ReportService $reportService) {}

    public function execute(array $filters = [])
    {
        return Excel::download(new AdminReportExport($filters ?: $this->reportService->defaultFilters()), 'reporte_administrativo.xlsx');
    }
}
