<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function indexWeb()
    {
        $auditorias = Auditoria::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.auditoria.index', compact('auditorias'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Auditoria::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(30));
    }
}
