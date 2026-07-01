@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Control de Pagos</h2>
    <p class="text-muted">Listado general de depósitos registrados por los aspirantes.</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pagos.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por aspirante..." value="{{ request('nombre') }}">
            </div>
            <div class="col-md-4">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
            <div class="col-md-4">
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
                            <div class="small fw-medium">{{ $p->inscripcion->aspirante?->nombre_completo ?? 'Aspirante eliminado' }}</div>
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
                            @if($p->estado === 'en_revision')
                            <div class="btn-group gap-1">
                                <form action="{{ route('admin.pagos.verificar', $p->id) }}" method="POST" class="d-inline" id="aprobarPago{{ $p->id }}">
                                    @csrf
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="showConfirmModal('aprobarPagoModal', function() { document.getElementById('aprobarPago{{ $p->id }}').submit(); })">
                                        <i class="fas fa-check me-1"></i> Aprobar
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="mostrarRechazo({{ $p->id }})">
                                    <i class="fas fa-times me-1"></i> Rechazar
                                </button>
                                <form action="{{ route('admin.pagos.verificar', $p->id) }}" method="POST" class="d-none" id="rechazarPago{{ $p->id }}">
                                    @csrf
                                    <input type="hidden" name="estado" value="rechazado">
                                    <input type="hidden" name="motivo_rechazo" id="motivo_rechazo{{ $p->id }}" value="">
                                </form>
                            </div>
                            @else
                            <a href="{{ route('admin.inscripciones.show', $p->inscripcion_id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Ver Detalle
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $pagos->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
    function mostrarRechazo(id) {
        Swal.fire({
            title: 'Motivo de Rechazo',
            input: 'textarea',
            inputLabel: 'Indica el motivo del rechazo:',
            inputPlaceholder: 'Escribe el motivo aquí...',
            inputAttributes: { required: true },
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Rechazar',
            cancelButtonText: 'Cancelar',
            borderRadius: '15px',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Debes indicar un motivo');
                }
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('motivo_rechazo' + id).value = result.value;
                document.getElementById('rechazarPago' + id).submit();
            }
        });
    }
</script>
@endsection

@push('modals')
<x-confirm-modal
    id="aprobarPagoModal"
    title="Confirmar aprobación"
    message="¿Estás seguro de aprobar este pago? Esta acción actualizará el estado de la inscripción si los documentos también están verificados."
    icon="fa-check-circle"
    iconColor="#198754"
    confirmText="Sí, aprobar"
    confirmClass="btn-success"
    confirmIcon="fa-check"
/>
@endpush
