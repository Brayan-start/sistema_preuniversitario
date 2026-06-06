@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Auditoría del Sistema</h2>
    <p class="text-muted">Registro detallado de acciones realizadas por los usuarios.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase fw-bold text-muted">
                        <th class="ps-4">Usuario</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditorias as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                            <div class="text-muted small">{{ $log->user->email }}</div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2">
                                {{ $log->accion }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ $log->descripcion }}</small></td>
                        <td class="small">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $auditorias->links() }}
        </div>
    </div>
</div>
@endsection
