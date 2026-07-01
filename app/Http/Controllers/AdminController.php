<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Curso;
use App\Models\Aspirante;
use App\Models\Auditoria;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $kpis = [
            'total_inscritos' => Inscripcion::has('aspirante')->count(),
            'inscritos_aprobados' => Inscripcion::has('aspirante')->where('estado', 'aprobado')->count(),
            'pagos_pendientes' => Pago::whereHas('inscripcion.aspirante')->where('estado', 'en_revision')->count(),
            'cupos_totales' => Curso::sum('cupos_disponibles'),
        ];

        $inscripciones_por_estado = Inscripcion::has('aspirante')->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        $ultimas_inscripciones = Inscripcion::has('aspirante')->with(['aspirante', 'curso'])
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
        $ultimas_inscripciones = Inscripcion::has('aspirante')->with(['curso', 'aspirante'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ultimos_aspirantes = Aspirante::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $kpis = [
            'total_aspirantes' => Aspirante::count(),
            'total_cursos' => Curso::count(),
            'inscripciones_pendientes' => Inscripcion::has('aspirante')->where('estado', 'pendiente')->count(),
            'inscripciones_aprobadas' => Inscripcion::has('aspirante')->where('estado', 'aprobado')->count(),
            'pagos_pendientes' => Pago::whereHas('inscripcion.aspirante')->where('estado', 'en_revision')->count(),
            'documentos_pendientes' => Documento::whereHas('aspirante')->where('estado', 'pendiente')->count(),
        ];

        return view('admin.dashboard', compact('ultimas_inscripciones', 'ultimos_aspirantes', 'kpis'));
    }

    public function aspirantesList(Request $request)
    {
        $query = Aspirante::with('user');

        if ($request->has('nombre')) {
            $query->where('nombre_completo', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('ci')) {
            $query->where('ci', 'like', '%' . $request->ci . '%');
        }

        $aspirantes = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.aspirantes.index', compact('aspirantes'));
    }

    public function updateAspiranteStatus(Request $request, Aspirante $aspirante)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $aspirante->load('user');

        if (!$aspirante->user) {
            return back()->with('error', 'No se encontró la cuenta asociada al aspirante.');
        }

        $aspirante->user->update([
            'is_active' => (bool) $validated['is_active'],
        ]);

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Cambio de estado de aspirante',
            'descripcion' => sprintf(
                'El aspirante %s fue marcado como %s.',
                $aspirante->nombre_completo,
                $aspirante->user->is_active ? 'activo' : 'inactivo'
            ),
        ]);

        return back()->with('success', 'Estado del aspirante actualizado correctamente.');
    }

    public function destroyAspirante($id)
    {
        $aspirante = Aspirante::with('user')->findOrFail($id);
        $nombre = $aspirante->nombre_completo;

        // Check if aspirante has active inscriptions
        $inscripcionesActivas = \App\Models\Inscripcion::where('aspirante_id', $id)->count();
        if ($inscripcionesActivas > 0) {
            // Soft delete the aspirante anyway — but the inscriptions remain
            // The views now handle this gracefully showing "Aspirante eliminado"
            \Log::info("Aspirante {$id} ({$nombre}) eliminado con {$inscripcionesActivas} inscripciones activas.");
        }

        $aspirante->delete();

        if ($aspirante->user) {
            $aspirante->user->update(['is_active' => false]);
        }

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación de aspirante',
            'descripcion' => "El aspirante {$nombre} fue eliminado (soft delete) del sistema.",
        ]);

        $msg = $inscripcionesActivas > 0
            ? "El aspirante {$nombre} ha sido eliminado. Tenía {$inscripcionesActivas} inscripción(es) activa(s) que ahora muestran 'Aspirante eliminado'."
            : "El aspirante {$nombre} ha sido eliminado correctamente.";

        return redirect()->route('admin.aspirantes.index')->with('success', $msg);
    }
}
