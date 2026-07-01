<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Curso;
use App\Models\Auditoria;
use App\Models\Aspirante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\InscripcionEstadoCambiado;
use App\Services\NotificationEmailService;

class InscripcionController extends Controller
{
    protected $emailService;

    public function __construct(NotificationEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function index(Request $request)
    {
        $query = Inscripcion::has('aspirante')->with(['aspirante', 'curso', 'pago']);

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'curso_id' => 'required|exists:cursos,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $curso = Curso::find($request->curso_id);
        
        if (!$curso->is_active || $curso->cupos_disponibles <= 0) {
            return response()->json(['error' => 'El curso no está disponible o no tiene cupos.'], 400);
        }

        $aspirante = auth()->user()->aspirante;

        $inscripcionExistente = Inscripcion::where('aspirante_id', $aspirante->id)->first();

        if ($inscripcionExistente) {
            return response()->json(['error' => 'Ya tiene una inscripción registrada en el sistema.'], 400);
        }

        $inscripcion = Inscripcion::create([
            'aspirante_id' => $aspirante->id,
            'curso_id' => $request->curso_id,
            'estado' => 'pendiente',
        ]);

        $curso->decrement('cupos_disponibles');

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Nueva inscripción (API)',
            'descripcion' => "El aspirante {$aspirante->nombre_completo} se ha inscrito al curso {$curso->nombre_curso}.",
        ]);

        return response()->json($inscripcion->load('curso'), 201);
    }

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
        $query = Inscripcion::has('aspirante')->with(['aspirante', 'curso']);

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

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $inscripciones = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
        return view('admin.inscripciones.index', compact('inscripciones'));
    }

    public function verInscripcion($id)
    {
        $inscripcion = Inscripcion::has('aspirante')->with(['aspirante', 'curso', 'pago', 'documentos'])->findOrFail($id);
        return view('admin.inscripciones.show', compact('inscripcion'));
    }

    public function validarInscripcion(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado,en_revision',
            'motivo_rechazo' => 'required_if:estado,rechazado',
            'grupo' => 'required_if:estado,aprobado',
        ]);

        $inscripcion = Inscripcion::has('aspirante')->with(['aspirante.user', 'curso'])->findOrFail($id);
        $oldEstado = $inscripcion->estado;

        $inscripcion->update([
            'estado' => $request->estado,
            'motivo_rechazo' => $request->motivo_rechazo,
            'grupo' => $request->grupo,
            'fecha_cambio_estado' => now(),
            'admin_responsable_id' => auth()->id(),
        ]);

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Validación de inscripción (Web)',
            'descripcion' => "Inscripción #{$id} cambiada de {$oldEstado} a {$request->estado}.",
        ]);

        $user = $inscripcion->aspirante?->user;
        if ($user) {
            $this->emailService->send(
                $user, 
                new InscripcionEstadoCambiado($inscripcion), 
                'cambio_estado_inscripcion', 
                auth()->id()
            );
        }

        return redirect()->route('admin.inscripciones.index')->with('success', 'Inscripción actualizada correctamente.');
    }

    public function misInscripciones()
    {
        $aspirante = auth()->user()->aspirante;
        $inscripciones = Inscripcion::with(['curso', 'pago', 'documentos'])
            ->where('aspirante_id', $aspirante->id)
            ->get();

        return response()->json($inscripciones);
    }

    public function show($id)
    {
        $aspirante = auth()->user()->aspirante;
        $inscripcion = Inscripcion::with(['curso', 'pago', 'documentos'])
            ->where('aspirante_id', $aspirante->id)
            ->findOrFail($id);

        return response()->json($inscripcion);
    }

    public function showAdmin($id)
    {
        $inscripcion = Inscripcion::has('aspirante')->with(['aspirante', 'curso', 'pago', 'documentos'])
            ->findOrFail($id);

        return response()->json($inscripcion);
    }

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

        $inscripcion = Inscripcion::has('aspirante')->with(['aspirante.user', 'curso'])->findOrFail($id);
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

        $user = $inscripcion->aspirante?->user;
        if ($user) {
            $this->emailService->send(
                $user, 
                new InscripcionEstadoCambiado($inscripcion), 
                'cambio_estado_inscripcion', 
                auth()->id()
            );
        }

        return response()->json($inscripcion);
    }
}
