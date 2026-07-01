@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Gestión de Cursos</h2>
        <p class="text-muted small">Administra las convocatorias vigentes para el preuniversitario.</p>
    </div>
    <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-plus me-2"></i> Nuevo Curso
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase fw-bold text-muted">
                        <th class="ps-4">Nombre del Curso</th>
                        <th>Periodo</th>
                        <th>Inversión</th>
                        <th>Cupos</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cursos as $curso)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $curso->nombre_curso }}</div>
                            <div class="text-muted small">{{ Str::limit($curso->descripcion, 50) }}</div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</div>
                            <div class="text-muted x-small">al {{ \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') }}</div>
                        </td>
                        <td><span class="fw-bold text-primary">Bs. {{ number_format($curso->monto_arancel, 2) }}</span></td>
                        <td>
                            <span class="badge bg-light text-dark border fw-normal px-2">
                                <i class="fas fa-user-friends me-1"></i> {{ $curso->cupos_disponibles }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $curso->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border-0 px-3 py-2 rounded-pill small">
                                {{ $curso->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-white btn-sm px-3 border-end" title="Editar">
                                    <i class="fas fa-edit text-warning"></i>
                                </a>
                                <button type="button" class="btn btn-white btn-sm px-3" onclick="confirmDelete({{ $curso->id }}, '{{ $curso->nombre_curso }}')" title="Eliminar">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                                <form id="delete-form-{{ $curso->id }}" action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No data" style="max-width: 80px; opacity: 0.3;" class="mb-3 d-block mx-auto">
                            <p class="text-muted">No hay cursos registrados actualmente.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `Eliminarás el curso: ${name}. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection
