@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.cursos.index') }}" class="btn btn-white shadow-sm rounded-circle me-3">
                <i class="fas fa-arrow-left text-primary"></i>
            </a>
            <h2 class="fw-bold mb-0">Editar Convocatoria</h2>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Nombre de la Convocatoria</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="nombre_curso" class="form-control @error('nombre_curso') is-invalid @enderror" value="{{ old('nombre_curso', $curso->nombre_curso) }}" required>
                        </div>
                        @error('nombre_curso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Descripción Detallada</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4" required>{{ old('descripcion', $curso->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Fecha de Inicio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio', $curso->fecha_inicio) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Fecha de Finalización</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" value="{{ old('fecha_fin', $curso->fecha_fin) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Costo del Arancel (Bs.)</label>
                            <div class="input-group">
                                <span class="input-group-text"><b>Bs.</b></span>
                                <input type="number" step="0.01" name="monto_arancel" class="form-control @error('monto_arancel') is-invalid @enderror" value="{{ old('monto_arancel', $curso->monto_arancel) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Cupos Totales</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <input type="number" name="cupos_disponibles" class="form-control @error('cupos_disponibles') is-invalid @enderror" value="{{ old('cupos_disponibles', $curso->cupos_disponibles) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Requisitos de Inscripción</label>
                        <textarea name="requisitos" class="form-control @error('requisitos') is-invalid @enderror" rows="3" required>{{ old('requisitos', $curso->requisitos) }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-uppercase">Estado del Curso</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_active" id="active1" value="1" {{ $curso->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="active1">Vigente (Activo)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_active" id="active0" value="0" {{ !$curso->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="active0">Finalizado (Inactivo)</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning py-3 rounded-pill shadow text-dark fw-bold">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
