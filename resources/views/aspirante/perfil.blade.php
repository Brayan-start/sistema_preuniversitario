@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h2 class="fw-bold">Mi Perfil</h2>
            <p class="text-muted">Mantén tu información actualizada para recibir notificaciones importantes.</p>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-5">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-section-title mb-4"><i class="fas fa-id-card me-2"></i> Datos de Usuario</div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Correo Electrónico</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            <small class="text-muted">El correo no se puede modificar.</small>
                        </div>
                    </div>

                    <div class="form-section-title mb-4 mt-4"><i class="fas fa-user-graduate me-2"></i> Información Académica</div>
                    @if($aspirante)
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Celular de Contacto</label>
                            <input type="text" name="celular" class="form-control" value="{{ old('celular', $aspirante->celular) }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Cédula de Identidad</label>
                            <input type="text" class="form-control bg-light" value="{{ $aspirante->ci }}" readonly>
                        </div>
                        <div class="col-md-8 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Colegio de Procedencia</label>
                            <input type="text" name="colegio_procedencia" class="form-control" value="{{ old('colegio_procedencia', $aspirante->colegio_procedencia) }}" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-bold text-uppercase">Año de Egreso</label>
                            <input type="number" name="anio_egreso" class="form-control" value="{{ old('anio_egreso', $aspirante->anio_egreso) }}" required>
                        </div>
                    </div>
                    @endif

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary py-3 rounded-pill shadow">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
