@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Gestión de Inscripciones</h2>
    <p class="text-muted">Revisa y valida las postulaciones de los aspirantes.</p>
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
                            <div class="fw-bold text-dark">{{ $i->aspirante->nombre_completo }}</div>
                            <div class="text-muted small">CI: {{ $i->aspirante->ci }}</div>
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
            {{ $inscripciones->links() }}
        </div>
    </div>
</div>
@endsection
