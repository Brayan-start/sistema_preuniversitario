<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function indexWeb()
    {
        $cursos = Curso::all();
        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.cursos.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'nombre_curso' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'monto_arancel' => 'required|numeric',
            'cupos_disponibles' => 'required|integer|min:0',
            'requisitos' => 'required|string',
            'horario' => 'nullable|string|max:200',
        ]);

        $curso = Curso::create($request->all());

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Creación de curso (Web)',
            'descripcion' => "Curso '{$curso->nombre_curso}' creado desde la web.",
        ]);

        return redirect()->route('admin.cursos.index')->with('success', 'Curso creado exitosamente.');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexActive()
    {
        $cursos = Curso::where('is_active', true)
            ->where('cupos_disponibles', '>', 0)
            ->get();

        return response()->json($cursos);
    }

    /**
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('admin.cursos.edit', compact('curso'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWeb(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        
        $request->validate([
            'nombre_curso' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'monto_arancel' => 'required|numeric',
            'cupos_disponibles' => 'required|integer|min:0',
            'requisitos' => 'required|string',
            'horario' => 'nullable|string|max:200',
            'is_active' => 'required|boolean',
        ]);

        $curso->update($request->all());

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Actualización de curso (Web)',
            'descripcion' => "Curso '{$curso->nombre_curso}' actualizado desde la web.",
        ]);

        return redirect()->route('admin.cursos.index')->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyWeb($id)
    {
        $curso = Curso::findOrFail($id);
        $nombre = $curso->nombre_curso;
        $curso->delete();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Eliminación de curso (Web)',
            'descripcion' => "Curso '{$nombre}' eliminado desde la web.",
        ]);

        return redirect()->route('admin.cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}
