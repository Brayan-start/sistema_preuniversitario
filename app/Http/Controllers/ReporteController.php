<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InscripcionesExport;

use App\Models\Curso;

class ReporteController extends Controller
{
    public function indexWeb()
    {
        $cursos = Curso::all();
        return view('admin.reportes.index', compact('cursos'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function inscripciones(Request $request)
    {
        $query = Inscripcion::with(['aspirante', 'curso', 'pago']);

        if ($request->has('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_desde') && $request->has('fecha_hasta')) {
            $query->whereBetween('created_at', [$request->fecha_desde, $request->fecha_hasta]);
        }

        return response()->json($query->get());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagos(Request $request)
    {
        $query = Pago::with(['inscripcion.aspirante', 'inscripcion.curso']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_desde') && $request->has('fecha_hasta')) {
            $query->whereBetween('fecha_pago', [$request->fecha_desde, $request->fecha_hasta]);
        }

        return response()->json($query->get());
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $inscripciones = Inscripcion::with(['aspirante', 'curso', 'pago'])->get();
        $pdf = Pdf::loadView('reports.inscripciones_pdf', compact('inscripciones'));
        return $pdf->download('reporte_inscripciones.pdf');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel()
    {
        return Excel::download(new InscripcionesExport, 'reporte_inscripciones.xlsx');
    }
}
