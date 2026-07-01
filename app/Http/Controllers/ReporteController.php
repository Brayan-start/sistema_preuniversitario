<?php

namespace App\Http\Controllers;

use App\Actions\Admin\GenerateExcelReportAction;
use App\Actions\Admin\GeneratePdfReportAction;
use App\Actions\Admin\RecordAuditAction;
use App\Http\Requests\Admin\DateRangeRequest;
use App\Services\Admin\ReportService;
use Illuminate\Http\Request;
use App\Models\Curso;

class ReporteController extends Controller
{
    public function indexWeb(ReportService $reportService)
    {
        return view('admin.reportes.index', [
            'cursos' => Curso::all(),
            'filters' => $reportService->defaultFilters(),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function inscripciones(Request $request, ReportService $reportService)
    {
        return response()->json($reportService->inscripciones($request->all()));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagos(Request $request, ReportService $reportService)
    {
        return response()->json($reportService->pagos($request->all()));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(DateRangeRequest $request, ReportService $reportService, GeneratePdfReportAction $action, RecordAuditAction $auditAction)
    {
        $auditAction->execute($request->user()->id, 'generar_pdf', 'reportes', 'Generacion de reporte PDF administrativo.', $request->ip());

        return $action->execute($request->validated(), $request->user())->download('reporte_inscripciones.pdf');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(DateRangeRequest $request, GenerateExcelReportAction $action, RecordAuditAction $auditAction)
    {
        $auditAction->execute($request->user()->id, 'exportar_excel', 'reportes', 'Exportacion de reporte Excel administrativo.', $request->ip());

        return $action->execute($request->validated());
    }
}
