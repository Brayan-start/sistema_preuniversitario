@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Gestión de Inscripciones</h2>
    <p class="text-muted">Revisa y valida las postulaciones de los aspirantes.</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="ci" class="form-control" placeholder="Buscar por CI..." value="{{ request('ci') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase fw-bold text-muted">
                        <th class="ps-4">Aspirante</th>
                        <th>Curso</th>
                        <th>Fecha Solicitud</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscripciones as $i)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $i->aspirante?->nombre_completo ?? 'Aspirante eliminado' }}</div>
                            <div class="text-muted small">CI: {{ $i->aspirante?->ci ?? '---' }}</div>
                        </td>
                        <td><span class="small fw-medium">{{ $i->curso->nombre_curso }}</span></td>
                        <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $statusColor = match($i->estado) {
                                    'aprobado' => 'success',
                                    'rechazado' => 'danger',
                                    'en_revision' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} rounded-pill px-3 py-2 small">
                                {{ strtoupper($i->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.inscripciones.show', $i->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="fas fa-search me-1"></i> Revisar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $inscripciones->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
