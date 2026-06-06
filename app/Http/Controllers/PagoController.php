<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Inscripcion;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Mail\PagoAprobado;
use App\Mail\PagoRechazado;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PagoController extends Controller
{
    public function showComprobante(Pago $pago): StreamedResponse
    {
        $user = auth()->user();
        $pago->load('inscripcion');

        if (!$user->isAdmin() && $pago->inscripcion->aspirante_id !== optional($user->aspirante)->id) {
            abort(403);
        }

        abort_unless(Storage::exists($pago->comprobante_path), 404);

        return Storage::download($pago->comprobante_path);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Pago::with(['inscripcion.aspirante', 'inscripcion.curso']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        return response()->json($query->paginate(15));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexWeb()
    {
        $aspirante = auth()->user()->aspirante;
        $inscripcion = Inscripcion::with('pago')->where('aspirante_id', $aspirante->id)->first();
        
        return view('aspirante.pagos.index', compact('inscripcion'));
    }

    public function indexAdmin()
    {
        $pagos = Pago::with(['inscripcion.aspirante', 'inscripcion.curso'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pagos.index', compact('pagos'));
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

        $path = $request->file('comprobante')->store('private/pagos');

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

        return redirect()->route('aspirante.dashboard')->with('success', 'Pago registrado exitosamente. Será verificado por el administrador.');
    }

    public function verificarWeb(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'motivo_rechazo' => 'required_if:estado,rechazado',
        ]);

        $pago = Pago::findOrFail($id);
        $pago->update([
            'estado' => $request->estado,
            'motivo_rechazo' => $request->motivo_rechazo,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Pago verificado correctamente.');
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
            'motivo_rechazo' => 'required_if:estado,rechazado',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pago = Pago::with('inscripcion.aspirante')->findOrFail($id);
        $pago->estado = $request->estado;
        $pago->motivo_rechazo = $request->motivo_rechazo;
        $pago->admin_id = auth()->id();
        $pago->save();

        Auditoria::create([
            'user_id' => auth()->id(),
            'accion' => 'Verificación de pago',
            'descripcion' => "Pago #{$id} marcado como {$request->estado}.",
        ]);

        if ($request->estado === 'aprobado') {
            Mail::to($pago->inscripcion->aspirante->correo)->queue(new PagoAprobado($pago));
        } else {
            Mail::to($pago->inscripcion->aspirante->correo)->queue(new PagoRechazado($pago));
        }

        return response()->json($pago);
    }
}
