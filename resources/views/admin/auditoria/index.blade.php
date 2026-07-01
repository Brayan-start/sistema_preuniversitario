@extends('layouts.app')

@section('content')
    <div class="dashboard-header p-4 p-lg-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
            <div>
                <span class="badge bg-light text-dark rounded-pill mb-3">Auditoría</span>
                <h2 class="fw-bold mb-2">Registro de Eventos</h2>
                <p class="mb-0 text-white-50">Seguimiento de acciones relevantes realizadas en el sistema.</p>
            </div>
        </div>
    </div>

    <div class="app-surface p-4 mb-4">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.auditoria.index') }}">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Usuario</label>
                <select name="user_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($users as $id => $name)
                        <option value="{{ $id }}" @selected(request('user_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Acción</label>
                <input type="text" name="accion" class="form-control" value="{{ request('accion') }}"
                    placeholder="Ej: Exportación">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Fecha desde</label>
                <input type="date" name="fecha_desde" class="form-control"
                    value="{{ request('fecha_desde', $filters['fecha_desde'] ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Fecha hasta</label>
                <input type="date" name="fecha_hasta" class="form-control"
                    value="{{ request('fecha_hasta', $filters['fecha_hasta'] ?? '') }}">
            </div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end">
                <button class="btn btn-primary rounded-pill px-4">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="app-surface p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" data-admin-audit-table>
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Acción</th>
                        <th>Módulo / Descripción</th>
                        <th>Fecha y hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditorias as $log)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $log->user->name }}</td>
                            <td><span
                                    class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $log->accion }}</span>
                            </td>
                            <td class="text-muted small">{{ $log->descripcion }}</td>
                            <td class="small">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 p-lg-4">
            {{ $auditorias->links() }}
        </div>
    </div>
@endsection
