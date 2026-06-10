@extends('layouts.app')

@section('content')
    <div class="dashboard-header p-4 p-lg-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
            <div>
                <span class="badge bg-light text-dark rounded-pill mb-3">Búsqueda Avanzada</span>
                <h2 class="fw-bold mb-2">Buscador Dinámico de Aspirantes</h2>
                <p class="mb-0 text-white-50">Filtra por cualquier campo existente sin modificar la lógica principal.</p>
            </div>
        </div>
    </div>

    <div class="app-surface p-4 mb-4">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.aspirantes.search') }}">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Campo</label>
                <select name="field" class="form-select">
                    @foreach ($searchFields as $field)
                        <option value="{{ $field }}" @selected(request('field') === $field)>{{ $field }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-uppercase">Operador</label>
                <select name="operator" class="form-select">
                    @foreach ($searchOperators as $operator)
                        <option value="{{ $operator }}" @selected(request('operator') === $operator)>{{ $operator }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Valor</label>
                <input type="text" name="value" class="form-control" value="{{ request('value') }}"
                    placeholder="Escribe el criterio">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary rounded-pill">Buscar</button>
            </div>
        </form>
    </div>

    <div class="app-surface p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" data-admin-search-table>
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>CI</th>
                        <th>Correo</th>
                        <th>Celular</th>
                        <th>Colegio</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results['items'] as $aspirante)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $aspirante->nombre_completo }}</td>
                            <td>{{ $aspirante->ci }}</td>
                            <td>{{ $aspirante->correo }}</td>
                            <td>{{ $aspirante->celular }}</td>
                            <td>{{ $aspirante->colegio_procedencia }}</td>
                            <td class="text-muted small">{{ $aspirante->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 p-lg-4">
            {{ $results['items']->links() }}
        </div>
    </div>
@endsection
