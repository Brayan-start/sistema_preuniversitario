<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aspirante;

use App\Models\Inscripcion;
use App\Models\Curso;

class AspiranteController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPerfil()
    {
        $user = auth()->user();
        $aspirante = Aspirante::with('user')->where('user_id', $user->id)->firstOrFail();

        return response()->json($aspirante);
    }

    public function dashboardView()
    {
        $user = auth()->user();
        $aspirante = $user->aspirante;
        
        $inscripcion = Inscripcion::with(['documentos', 'pago', 'curso'])
            ->where('aspirante_id', $aspirante->id)
            ->first();

        return view('aspirante.dashboard', compact('inscripcion'));
    }

    public function cursosDisponibles()
    {
        $cursos = Curso::where('is_active', true)
            ->where('cupos_disponibles', '>', 0)
            ->get();
            
        return view('aspirante.cursos.index', compact('cursos'));
    }

    public function perfil()
    {
        $user = auth()->user();
        $aspirante = $user->aspirante;
        return view('aspirante.perfil', compact('user', 'aspirante'));
    }

    public function updatePerfil(Request $request)
    {
        $user = auth()->user();
        $aspirante = $user->aspirante;

        $request->validate([
            'name' => 'required|string|max:100',
            'celular' => 'required|string|max:20',
            'colegio_procedencia' => 'required|string|max:150',
            'anio_egreso' => 'required|digits:4',
        ]);

        $user->update(['name' => $request->name]);
        $aspirante->update($request->only(['celular', 'colegio_procedencia', 'anio_egreso']));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
