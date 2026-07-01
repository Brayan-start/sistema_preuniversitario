@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Verificación de Documentos</h2>
    <p class="text-muted">Revisa y valida los documentos subidos por los aspirantes.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.documentos.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <option value="ci" {{ request('tipo') == 'ci' ? 'selected' : '' }}>Cédula de Identidad</option>
                    <option value="certificado_bachillerato" {{ request('tipo') == 'certificado_bachillerato' ? 'selected' : '' }}>Certificado de Bachillerato</option>
                    <option value="fotografia" {{ request('tipo') == 'fotografia' ? 'selected' : '' }}>Fotografía</option>
                </select>
            </div>
            <div class="col-md-3">
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
                        <th>Tipo Documento</th>
                        <th>Formato</th>
                        <th>Fecha Subida</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $doc)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $doc->aspirante?->nombre_completo ?? 'Aspirante eliminado' }}</div>
                            <div class="text-muted small">{{ $doc->aspirante?->correo ?? '---' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                {{ strtoupper(str_replace('_', ' ', $doc->tipo)) }}
                            </span>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($doc->formato) }}</span></td>
                        <td class="small">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $statusColor = match($doc->estado) {
                                    'aprobado' => 'success',
                                    'rechazado' => 'danger',
                                    default => 'warning'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} rounded-pill px-3 py-2 small">
                                {{ ucfirst($doc->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group gap-1">
                                <a href="{{ route('documentos.archivo', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                @if($doc->estado != 'aprobado')
                                <form action="{{ route('admin.documentos.verificar', $doc->id) }}" method="POST" class="d-inline" id="aprobarDoc{{ $doc->id }}">
                                    @csrf
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="confirmarAccion({{ $doc->id }}, 'aprobado')">
                                        <i class="fas fa-check me-1"></i> Aprobar
                                    </button>
                                </form>
                                @endif
                                @if($doc->estado != 'rechazado')
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="mostrarRechazo({{ $doc->id }})">
                                    <i class="fas fa-times me-1"></i> Rechazar
                                </button>
                                @endif
                            </div>
                            <form action="{{ route('admin.documentos.verificar', $doc->id) }}" method="POST" class="d-none" id="rechazarDoc{{ $doc->id }}">
                                @csrf
                                <input type="hidden" name="estado" value="rechazado">
                                <input type="hidden" name="observaciones" id="observaciones{{ $doc->id }}" value="">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-muted">No hay documentos registrados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $documentos->links() }}
        </div>
    </div>
</div>

<script>
    function confirmarAccion(id, accion) {
        const titulo = accion === 'aprobado' ? '¿Aprobar documento?' : '¿Rechazar documento?';
        const icono = accion === 'aprobado' ? 'success' : 'warning';
        Swal.fire({
            title: titulo,
            text: 'Esta acción notificará al aspirante.',
            icon: icono,
            showCancelButton: true,
            confirmButtonColor: accion === 'aprobado' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ' + accion,
            cancelButtonText: 'Cancelar',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(accion === 'aprobado' ? 'aprobarDoc' + id : 'rechazarDoc' + id).submit();
            }
        });
    }

    function mostrarRechazo(id) {
        Swal.fire({
            title: 'Motivo de Rechazo',
            input: 'textarea',
            inputLabel: 'Indica el motivo del rechazo:',
            inputPlaceholder: 'Escribe el motivo aquí...',
            inputAttributes: {
                required: true
            },
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
                document.getElementById('observaciones' + id).value = result.value;
                document.getElementById('rechazarDoc' + id).submit();
            }
        });
    }
</script>
@endsection
