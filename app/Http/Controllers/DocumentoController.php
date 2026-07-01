<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Inscripcion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\DocumentosObservados;
use App\Services\NotificationEmailService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DocumentoController extends Controller
{
    protected $emailService;

    public function __construct(NotificationEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function showFile(Documento $documento)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $documento->aspirante_id !== optional($user->aspirante)->id) {
            abort(403);
        }

        $url = $documento->archivo_path;

        if (empty($url) || $url === '0') {
            abort(404, 'El archivo no está disponible.');
        }

        return redirect()->away($url);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'ci' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'certificado_bachillerato' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'fotografia' => 'required|file|mimes:jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $inscripcion = Inscripcion::findOrFail($request->inscripcion_id);
        $aspirante = auth()->user()->aspirante;

        if ($inscripcion->aspirante_id !== $aspirante->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $tipos = ['ci', 'certificado_bachillerato', 'fotografia'];
        $documentos = [];

        foreach ($tipos as $tipo) {
            $file = $request->file($tipo);
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'documentos',
            ]);
            $path = $upload->getSecurePath();

            $doc = Documento::create([
                'aspirante_id' => $aspirante->id,
                'inscripcion_id' => $inscripcion->id,
                'tipo' => $tipo,
                'archivo_path' => $path,
                'formato' => $file->getClientOriginalExtension(),
                'estado' => 'pendiente',
            ]);
            $documentos[] = $doc;
        }

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Carga de documentos (API)',
            'descripcion' => "Documentos cargados para la inscripción #{$inscripcion->id}.",
        ]);

        return response()->json($documentos, 201);
    }

    public function indexWeb()
    {
        $aspirante = auth()->user()->aspirante;
        $inscripcion = Inscripcion::with('documentos')->where('aspirante_id', $aspirante->id)->first();
        
        return view('aspirante.documentos.index', compact('inscripcion'));
    }

    public function uploadWeb(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'ci' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'certificado_bachillerato' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
            'fotografia' => 'required|file|mimes:jpg,jpeg|max:2048',
        ]);

        $inscripcion = Inscripcion::findOrFail($request->inscripcion_id);
        $aspirante = auth()->user()->aspirante;

        if ($inscripcion->aspirante_id !== $aspirante->id) {
            return back()->with('error', 'No autorizado');
        }

        $tipos = ['ci', 'certificado_bachillerato', 'fotografia'];

        foreach ($tipos as $tipo) {
            $file = $request->file($tipo);
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'documentos',
            ]);
            $path = $upload->getSecurePath();

            Documento::create([
                'aspirante_id' => $aspirante->id,
                'inscripcion_id' => $inscripcion->id,
                'tipo' => $tipo,
                'archivo_path' => $path,
                'formato' => $file->getClientOriginalExtension(),
                'estado' => 'pendiente',
            ]);
        }

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Carga de documentos (Web)',
            'descripcion' => "Documentos cargados para la inscripción #{$inscripcion->id}.",
        ]);

        return redirect()->route('aspirante.dashboard')->with('success', 'Documentos cargados exitosamente.');
    }

    public function indexAdminWeb(Request $request)
    {
        $query = Documento::with(['aspirante', 'inscripcion.curso'])->has('aspirante');

        if ($request->has('nombre')) {
            $query->whereHas('aspirante', function($q) use ($request) {
                $q->where('nombre_completo', 'like', '%' . $request->nombre . '%');
            });
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo', $request->tipo);
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.documentos.index', compact('documentos'));
    }

    public function verificarWeb(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'observaciones' => 'required_if:estado,rechazado',
        ]);

        $documento = Documento::with(['aspirante.user', 'inscripcion'])->findOrFail($id);
        $documento->estado = $request->estado;
        $documento->save();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Verificación de documento (Web)',
            'descripcion' => "Documento #{$id} ({$documento->tipo}) marcado como {$request->estado}.",
        ]);

        if ($request->estado === 'rechazado') {
            $user = $documento->aspirante?->user;
            if ($user) {
                $this->emailService->send(
                    $user, 
                    new DocumentosObservados($documento, $request->observaciones), 
                    'documentos_observados', 
                    auth()->id()
                );
            }
        }

        return redirect()->route('admin.documentos.index')->with('success', 'Documento ' . ($request->estado === 'aprobado' ? 'aprobado' : 'rechazado') . ' correctamente.');
    }

    public function verificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:aprobado,rechazado',
            'observaciones' => 'required_if:estado,rechazado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $documento = Documento::with('aspirante.user')->findOrFail($id);
        $documento->estado = $request->estado;
        $documento->save();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Verificación de documento',
            'descripcion' => "Documento #{$id} ({$documento->tipo}) marcado como {$request->estado}.",
        ]);

        if ($request->estado === 'rechazado') {
            $user = $documento->aspirante?->user;
            if ($user) {
                $this->emailService->send(
                    $user, 
                    new DocumentosObservados($documento, $request->observaciones), 
                    'documentos_observados', 
                    auth()->id()
                );
            }
        }

        return response()->json($documento);
    }
}
