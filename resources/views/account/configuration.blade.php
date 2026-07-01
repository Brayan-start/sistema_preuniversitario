@extends('layouts.app')

@section('content')
@php
    $photoUrl = $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : null;
@endphp

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="mb-4">
            <h2 class="fw-bold">Configuración</h2>
            <p class="text-muted">Administra los datos, seguridad y preferencias básicas de tu cuenta.</p>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                No se pudieron guardar los cambios. Revisa los campos marcados.
            </div>
        @endif

        <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Foto de perfil" class="rounded-circle object-fit-cover mb-3 border shadow-sm" style="width: 80px; height: 80px;">
                            @else
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 border shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <label class="form-label small fw-bold text-uppercase">Actualizar foto</label>
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/png,image/jpeg">
                            @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <p class="text-muted small mt-3 mb-0">Formatos permitidos: JPG o PNG. Tamaño máximo: 2 MB.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 fw-bold">Datos de Cuenta</div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Nombre</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Correo</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            @if($user->isAspirante() && $aspirante)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-uppercase">Nombre completo</label>
                                        <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ old('nombre_completo', $aspirante->nombre_completo) }}" required>
                                        @error('nombre_completo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold text-uppercase">Celular</label>
                                        <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror" value="{{ old('celular', $aspirante->celular) }}" required>
                                        @error('celular') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label small fw-bold text-uppercase">Colegio de procedencia</label>
                                        <input type="text" name="colegio_procedencia" class="form-control @error('colegio_procedencia') is-invalid @enderror" value="{{ old('colegio_procedencia', $aspirante->colegio_procedencia) }}" required>
                                        @error('colegio_procedencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-bold text-uppercase">Año de egreso</label>
                                        <input type="number" name="anio_egreso" class="form-control @error('anio_egreso') is-invalid @enderror" value="{{ old('anio_egreso', $aspirante->anio_egreso) }}" required>
                                        @error('anio_egreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="email_notifications" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="emailNotifications" name="email_notifications" value="1" {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">Recibir notificaciones por correo</label>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 fw-bold">Seguridad</div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Contraseña actual</label>
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Nueva contraseña</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold text-uppercase">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap mt-3">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-save me-2"></i> Guardar Cambios
                                </button>
                                <a href="{{ route('perfil') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fas fa-user me-2"></i> Ver Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
