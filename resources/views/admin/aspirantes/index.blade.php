@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Listado de Aspirantes</h2>
    <p class="text-muted">Todos los usuarios registrados en el sistema.</p>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $aspirantes->links() }}
        </div>
    </div>
</div>
@endsection
