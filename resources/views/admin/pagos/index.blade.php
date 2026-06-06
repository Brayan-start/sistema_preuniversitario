@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Control de Pagos</h2>
    <p class="text-muted">Listado general de depósitos registrados por los aspirantes.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase fw-bold text-muted">
                        <th class="ps-4">Comprobante</th>
                        <th>Aspirante</th>
                        <th>Monto</th>
                        <th>Fecha Depósito</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $p->numero_comprobante }}</div>
                            <div class="text-muted small">ID Pago: #{{ $p->id }}</div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $p->inscripcion->aspirante->nombre_completo }}</div>
                            <div class="text-muted x-small">{{ $p->inscripcion->curso->nombre_curso }}</div>
                        </td>
                        <td><span class="fw-bold text-success">Bs. {{ number_format($p->monto, 2) }}</span></td>
                        <td>{{ $p->fecha_pago }}</td>
                        <td class="text-center">
                            @php
                                $statusColor = match($p->estado) {
                                    'aprobado' => 'success',
                                    'rechazado' => 'danger',
                                    'en_revision' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} rounded-pill px-3 py-2 small">
                                {{ strtoupper($p->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.inscripciones.show', $p->inscripcion_id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $pagos->links() }}
        </div>
    </div>
</div>
@endsection
