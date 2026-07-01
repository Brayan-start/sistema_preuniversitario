@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Listado de Aspirantes</h2>
    <p class="text-muted">Todos los usuarios registrados en el sistema.</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.aspirantes.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="ci" class="form-control" placeholder="Buscar por CI..." value="{{ request('ci') }}">
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
                        <th class="ps-4">Aspirante</th>
                        <th>Cédula</th>
                        <th>Contacto</th>
                        <th>Colegio</th>
                        <th>Registro</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aspirantes as $a)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $a->nombre_completo }}</div>
                            <div class="text-muted small">{{ $a->user->email }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-normal px-2">{{ $a->ci }}</span></td>
                        <td>{{ $a->celular }}</td>
                        <td><div class="small">{{ $a->colegio_procedencia }} ({{ $a->anio_egreso }})</div></td>
                        <td class="small text-muted">{{ $a->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $a->user->is_active ? 'success' : 'danger' }}-subtle text-{{ $a->user->is_active ? 'success' : 'danger' }} rounded-pill px-3">
                                {{ $a->user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                <form action="{{ route('admin.aspirantes.estado', $a) }}" method="POST" class="d-inline-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                    <div class="form-check form-switch m-0">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            role="switch"
                                            id="aspiranteEstado{{ $a->id }}"
                                            name="is_active"
                                            value="1"
                                            {{ $a->user->is_active ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            aria-label="Cambiar estado de {{ $a->nombre_completo }}"
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Guardar
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="confirmarEliminacion({{ $a->id }}, '{{ addslashes($a->nombre_completo) }}')">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </button>
                                <form id="delete-form-{{ $a->id }}" action="{{ route('admin.aspirantes.destroy', $a->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $aspirantes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@push('scripts')
<script>
function confirmarEliminacion(id, nombre) {
    Swal.fire({
        title: '¿Eliminar aspirante?',
        html: `Se eliminará a <strong>${nombre}</strong>.<br><br>` +
              `<span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Esta acción desactivará su cuenta de usuario.</span>` +
              `<br><span class="text-muted small">Los registros de inscripciones, pagos y documentos se conservarán en la base de datos.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection
