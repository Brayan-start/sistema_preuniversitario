<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Curso;
use App\Models\Auditoria;
use App\Models\Aspirante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\InscripcionEstadoCambiado;
use Illuminate\Support\Facades\Mail;

class InscripcionController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Inscripcion::with(['aspirante', 'curso', 'pago']);

        if ($request->has('nombre')) {
            $query->whereHas('aspirante', function($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->nombre . '%');
            });
        }

        if ($request->has('ci')) {
            $query->whereHas('aspirante', function($q) use ($request) {
                $q->where('ci', 'like', '%' . $request->ci . '%');
            });
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        return response()->json($query->paginate(15));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $curso = Curso::find($request->curso_id);
        
        if (!$curso->is_active || $curso->cupos_disponibles <= 0) {
            return back()->with('error', 'El curso no está disponible o no tiene cupos.');
        }

        $aspirante = auth()->user()->aspirante;

        $inscripcionExistente = Inscripcion::where('aspirante_id', $aspirante->id)->first();

        if ($inscripcionExistente) {
            return back()->with('error', 'Ya tiene una inscripción registrada en el sistema.');
        }

        $inscripcion = Inscripcion::create([
            'aspirante_id' => $aspirante->id,
            'curso_id' => $request->curso_id,
            'estado' => 'pendiente',
        ]);

        $curso->decrement('cupos_disponibles');

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Nueva inscripción (Web)',
            'descripcion' => "El aspirante {$aspirante->nombre_completo} se ha inscrito al curso {$curso->nombre_curso}.",
        ]);

        return redirect()->route('aspirante.dashboard')->with('success', 'Inscripción realizada con éxito. Ahora debes subir tus requisitos.');
    }

    public function indexWeb(Request $request)
    {
        $inscripciones = Inscripcion::with(['aspirante', 'curso'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.inscripciones.index', compact('inscripciones'));
    }

    public function verInscripcion($id)
    {
        $inscripcion = Inscripcion::with(['aspirante', 'curso', 'pago', 'documentos'])->findOrFail($id);
        return view('admin.inscripciones.show', compact('inscripcion'));
    }

    public function validarInscripcion(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado,en_revision',
            'motivo_rechazo' => 'required_if:estado,rechazado',
            'grupo' => 'required_if:estado,aprobado',
        ]);

        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->update([
            'estado' => $request->estado,
            'motivo_rechazo' => $request->motivo_rechazo,
            'grupo' => $request->grupo,
            'fecha_cambio_estado' => now(),
            'admin_responsable_id' => auth()->id(),
        ]);

        return redirect()->route('admin.inscripciones.index')->with('success', 'Inscripción actualizada correctamente.');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function misInscripciones()
    {
        $aspirante = auth()->user()->aspirante;
        $inscripciones = Inscripcion::with(['curso', 'pago', 'documentos'])
            ->where('aspirante_id', $aspirante->id)
            ->get();

        return response()->json($inscripciones);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $aspirante = auth()->user()->aspirante;
        $inscripcion = Inscripcion::with(['curso', 'pago', 'documentos'])
            ->where('aspirante_id', $aspirante->id)
            ->findOrFail($id);

        return response()->json($inscripcion);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAdmin($id)
    {
        $inscripcion = Inscripcion::with(['aspirante', 'curso', 'pago', 'documentos'])
            ->findOrFail($id);

        return response()->json($inscripcion);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cambiarEstado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:aprobado,rechazado,en_revision',
            'motivo_rechazo' => 'required_if:estado,rechazado',
            'grupo' => 'required_if:estado,aprobado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $inscripcion = Inscripcion::findOrFail($id);
        $oldEstado = $inscripcion->estado;
        
        $inscripcion->estado = $request->estado;
        $inscripcion->motivo_rechazo = $request->motivo_rechazo;
        $inscripcion->grupo = $request->grupo;
        $inscripcion->fecha_cambio_estado = now();
        $inscripcion->admin_responsable_id = auth()->id();
        $inscripcion->save();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Cambio de estado de inscripción',
            'descripcion' => "Estado de la inscripción #{$id} cambiado de {$oldEstado} a {$request->estado}.",
        ]);

        // Enviar notificación por correo (Mailable)
        Mail::to($inscripcion->aspirante->correo)->queue(new InscripcionEstadoCambiado($inscripcion));

        return response()->json($inscripcion);
    }
}
