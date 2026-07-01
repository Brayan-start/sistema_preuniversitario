@extends('layouts.app')

@section('content')
@php
    $photoUrl = $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : null;
    $profileName = $aspirante?->nombre_completo ?? $user->name;
    $profileRole = $user->isAspirante() ? 'Aspirante' : ucfirst($user->role);
@endphp

<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold">Mi Perfil</h2>
        <p class="text-muted">Información principal de tu cuenta en el sistema.</p>
    </div>
</div>

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        Revisa los campos marcados antes de guardar.
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card profile-summary-card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Foto de perfil" class="profile-photo-lg rounded-circle object-fit-cover mb-3">
                @else
                    <div class="profile-avatar-lg rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        {{ strtoupper(substr($profileName, 0, 1)) }}
                    </div>
                @endif
                <h4 class="fw-bold mb-1">{{ $profileName }}</h4>
                <p class="text-muted mb-3 text-break">{{ $user->email }}</p>
                <span class="badge profile-role-badge rounded-pill px-3 py-2">
                    {{ $profileRole }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 fw-bold">Datos del Perfil</div>
            <div class="card-body p-4">
                @if($user->isAdmin())
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Nombre</label>
                            <div class="fw-medium">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Correo</label>
                            <div class="fw-medium">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Rol</label>
                            <div class="fw-medium">{{ ucfirst($user->role) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Fecha de creación</label>
                            <div class="fw-medium">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Última actividad</label>
                            <div class="fw-medium">
                                {{ $ultimaActividad ? $ultimaActividad->created_at->format('d/m/Y H:i:s').' - '.$ultimaActividad->accion : 'Sin actividad registrada' }}
                            </div>
                        </div>
                    </div>
                @else
                    @if($aspirante)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Nombre completo</label>
                            <div class="fw-medium">{{ $aspirante->nombre_completo }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">CI</label>
                            <div class="fw-medium">{{ $aspirante->ci }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Correo</label>
                            <div class="fw-medium">{{ $aspirante->correo }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Celular</label>
                            <div class="fw-medium">{{ $aspirante->celular }}</div>
                        </div>
                        <div class="col-md-8">
                            <label class="small text-muted text-uppercase fw-bold">Colegio de procedencia</label>
                            <div class="fw-medium">{{ $aspirante->colegio_procedencia }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-bold">Año de egreso</label>
                            <div class="fw-medium">{{ $aspirante->anio_egreso }}</div>
                        </div>
                    </div>
                    @else
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="text-muted">No se encontraron datos de aspirante.</div>
                        </div>
                    </div>
                    @endif
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Estado de inscripción</label>
                            <div class="fw-medium">
                                {{ $inscripcion ? ucfirst(str_replace('_', ' ', $inscripcion->estado)) : 'Sin inscripción registrada' }}
                            </div>
                        </div>
                        @if($inscripcion)
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Curso</label>
                            <div class="fw-medium">{{ $inscripcion->curso->nombre_curso }}</div>
                        </div>
                        @if($inscripcion->grupo)
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Paralelo / Grupo</label>
                            <div class="fw-medium">{{ $inscripcion->grupo }}</div>
                        </div>
                        @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 fw-bold">Actualizar Perfil</div>
            <div class="card-body p-4">
                <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase">Foto de perfil</label>
                        <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/png,image/jpeg">
                        @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-2"></i> Guardar Perfil
                    </button>
                    <a href="{{ route('configuracion') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">
                        <i class="fas fa-cog me-2"></i> Configuración
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
