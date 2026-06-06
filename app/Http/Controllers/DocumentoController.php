<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Inscripcion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Mail\DocumentosObservados;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentoController extends Controller
{
    public function showFile(Documento $documento): StreamedResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $documento->aspirante_id !== optional($user->aspirante)->id) {
            abort(403);
        }

        abort_unless(Storage::exists($documento->archivo_path), 404);

        return Storage::download($documento->archivo_path);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $path = $file->store('private/documentos');
            
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

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:aprobado,rechazado',
            'observaciones' => 'required_if:estado,rechazado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $documento = Documento::with('aspirante')->findOrFail($id);
        $documento->estado = $request->estado;
        $documento->save();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Verificación de documento',
            'descripcion' => "Documento #{$id} ({$documento->tipo}) marcado como {$request->estado}.",
        ]);

        if ($request->estado === 'rechazado') {
            Mail::to($documento->aspirante->correo)->queue(new DocumentosObservados($documento, $request->observaciones));
        }

        return response()->json($documento);
    }
}
