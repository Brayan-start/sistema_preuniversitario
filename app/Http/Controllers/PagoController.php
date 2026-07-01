<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Inscripcion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\PagoAprobado;
use App\Mail\PagoRechazado;
use App\Mail\PagoRecibidoMail;
use App\Services\NotificationEmailService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PagoController extends Controller
{
    protected $emailService;

    public function __construct(NotificationEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function showComprobante(Pago $pago)
    {
        $user = auth()->user();
        $pago->load('inscripcion');

        if (!$user->isAdmin() && $pago->inscripcion->aspirante_id !== optional($user->aspirante)->id) {
            abort(403);
        }

        $url = $pago->comprobante_path;

        if (empty($url)) {
            abort(404, 'El comprobante no está disponible.');
        }

        return redirect()->away($url);
    }

    public function index(Request $request)
    {
        $query = Pago::whereHas('inscripcion.aspirante')->with(['inscripcion.aspirante', 'inscripcion.curso']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        return response()->json($query->paginate(15));
    }

    public function indexWeb()
    {
        $aspirante = auth()->user()->aspirante;
        $inscripcion = Inscripcion::with('pago')->where('aspirante_id', $aspirante->id)->first();
        
        return view('aspirante.pagos.index', compact('inscripcion'));
    }

    public function indexAdmin(Request $request)
    {
        $query = Pago::whereHas('inscripcion.aspirante')->with(['inscripcion.aspirante', 'inscripcion.curso']);

        if ($request->has('nombre')) {
            $query->whereHas('inscripcion.aspirante', function($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->nombre . '%');
            });
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $pagos = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pagos.index', compact('pagos'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'numero_comprobante' => 'required|string|max:100',
            'comprobante' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $inscripcion = Inscripcion::findOrFail($request->inscripcion_id);
        
        if ($inscripcion->aspirante_id !== auth()->user()->aspirante->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($inscripcion->pago) {
            return response()->json(['error' => 'Ya has registrado un pago para esta inscripción.'], 400);
        }

        $upload = Cloudinary::upload($request->file('comprobante')->getRealPath(), [
            'folder' => 'pagos',
        ]);
        $path = $upload->getSecurePath();

        $pago = Pago::create([
            'inscripcion_id' => $inscripcion->id,
            'numero_comprobante' => $request->numero_comprobante,
            'comprobante_path' => $path,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'estado' => 'en_revision',
        ]);

        $inscripcion->update(['estado' => 'en_revision']);

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Registro de pago (API)',
            'descripcion' => "Pago registrado para la inscripción #{$inscripcion->id}. Comprobante: {$request->numero_comprobante}",
        ]);

        $this->emailService->send(
            auth()->user(), 
            new PagoRecibidoMail($pago), 
            'pago_recibido'
        );

        return response()->json($pago, 201);
    }

    public function storeWeb(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'numero_comprobante' => 'required|string|max:100',
            'comprobante' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
        ]);

        $inscripcion = Inscripcion::findOrFail($request->inscripcion_id);
        
        if ($inscripcion->aspirante_id !== auth()->user()->aspirante->id) {
            return back()->with('error', 'No autorizado');
        }

        if ($inscripcion->pago) {
            return back()->with('error', 'Ya has registrado un pago para esta inscripción.');
        }

        $upload = Cloudinary::upload($request->file('comprobante')->getRealPath(), [
            'folder' => 'pagos',
        ]);
        $path = $upload->getSecurePath();

        $pago = Pago::create([
            'inscripcion_id' => $inscripcion->id,
            'numero_comprobante' => $request->numero_comprobante,
            'comprobante_path' => $path,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'estado' => 'en_revision',
        ]);

        $inscripcion->update(['estado' => 'en_revision']);

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Registro de pago (Web)',
            'descripcion' => "Pago registrado para la inscripción #{$inscripcion->id}. Comprobante: {$request->numero_comprobante}",
        ]);

        $this->emailService->send(
            auth()->user(), 
            new PagoRecibidoMail($pago), 
            'pago_recibido'
        );

        return redirect()->route('aspirante.dashboard')->with('success', 'Pago registrado exitosamente. Será verificado por el administrador.');
    }

    public function verificarWeb(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'motivo_rechazo' => 'required_if:estado,rechazado',
        ]);

        $pago = Pago::whereHas('inscripcion.aspirante')->with('inscripcion.aspirante.user', 'inscripcion.documentos')->findOrFail($id);
        $pago->update([
            'estado' => $request->estado,
            'motivo_rechazo' => $request->motivo_rechazo,
            'admin_id' => auth()->id(),
        ]);

        if ($request->estado === 'aprobado') {
            $todosDocsAprobados = $pago->inscripcion->documentos->every(fn($d) => $d->estado === 'aprobado');
            if ($todosDocsAprobados) {
                $pago->inscripcion->update([
                    'estado' => 'aprobado',
                    'fecha_cambio_estado' => now(),
                    'admin_responsable_id' => auth()->id(),
                ]);
            }
        }

        $mailable = $request->estado === 'aprobado' ? new PagoAprobado($pago) : new PagoRechazado($pago);
        $user = $pago->inscripcion->aspirante?->user;
        if ($user) {
            $this->emailService->send($user, $mailable, 'pago_' . $request->estado, auth()->id());
        }

        return back()->with('success', 'Pago verificado correctamente.');
    }

    public function verificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:aprobado,rechazado',
            'motivo_rechazo' => 'required_if:estado,rechazado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pago = Pago::whereHas('inscripcion.aspirante')->with('inscripcion.aspirante.user', 'inscripcion.documentos')->findOrFail($id);
        $pago->estado = $request->estado;
        $pago->motivo_rechazo = $request->motivo_rechazo;
        $pago->admin_id = auth()->id();
        $pago->save();

        if ($request->estado === 'aprobado') {
            $todosDocsAprobados = $pago->inscripcion->documentos->every(fn($d) => $d->estado === 'aprobado');
            if ($todosDocsAprobados) {
                $pago->inscripcion->update([
                    'estado' => 'aprobado',
                    'fecha_cambio_estado' => now(),
                    'admin_responsable_id' => auth()->id(),
                ]);
            }
        }

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Verificación de pago',
            'descripcion' => "Pago #{$id} marcado como {$request->estado}.",
        ]);

        $mailable = $request->estado === 'aprobado' ? new PagoAprobado($pago) : new PagoRechazado($pago);
        $user = $pago->inscripcion->aspirante?->user;
        if ($user) {
            $this->emailService->send($user, $mailable, 'pago_' . $request->estado, auth()->id());
        }

        return response()->json($pago);
    }
}
