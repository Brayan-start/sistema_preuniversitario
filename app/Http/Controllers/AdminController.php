<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Curso;
use App\Models\Aspirante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
    {
        $kpis = [
            'total_inscritos' => Inscripcion::count(),
            'inscritos_aprobados' => Inscripcion::where('estado', 'aprobado')->count(),
            'pagos_pendientes' => Pago::where('estado', 'en_revision')->count(),
            'cupos_totales' => Curso::sum('cupos_disponibles'),
        ];

        $inscripciones_por_estado = Inscripcion::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        $ultimas_inscripciones = Inscripcion::with(['aspirante', 'curso'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'kpis' => $kpis,
            'grafico_estados' => $inscripciones_por_estado,
            'ultimas_inscripciones' => $ultimas_inscripciones,
        ]);
    }

    public function dashboardView()
    {
        $ultimas_inscripciones = Inscripcion::with(['aspirante', 'curso'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ultimos_aspirantes = Aspirante::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $kpis = [
            'total_aspirantes' => Aspirante::count(),
            'total_cursos' => Curso::count(),
            'inscripciones_pendientes' => Inscripcion::where('estado', 'pendiente')->count(),
            'inscripciones_aprobadas' => Inscripcion::where('estado', 'aprobado')->count(),
        ];

        return view('admin.dashboard', compact('ultimas_inscripciones', 'ultimos_aspirantes', 'kpis'));
    }

    public function aspirantesList()
    {
        $aspirantes = Aspirante::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.aspirantes.index', compact('aspirantes'));
    }
}
