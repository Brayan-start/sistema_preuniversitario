<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\DateRangeRequest;
use App\Services\Admin\AuditService;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function indexWeb(DateRangeRequest $request, AuditService $auditService)
    {
        return view('admin.auditoria.index', [
            'auditorias' => $auditService->paginate($request->validated()),
            'filters' => $auditService->defaultFilters(),
            'users' => $auditService->users(),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, AuditService $auditService)
    {
        return response()->json($auditService->paginate($request->all()));
    }
}
