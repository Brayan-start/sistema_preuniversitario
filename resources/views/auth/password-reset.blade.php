@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white p-4 text-center">
                <h4 class="fw-bold mb-0">Restablecer Contraseña</h4>
            </div>
            <div class="card-body p-5">
                @if(!isset($token))
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required placeholder="tu@correo.com">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">
                            <i class="fas fa-paper-plane me-2"></i> Enviar Enlace de Recuperación
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required placeholder="tu@correo.com" value="{{ $email ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">
                            <i class="fas fa-save me-2"></i> Restablecer Contraseña
                        </button>
                    </form>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none small">
                        <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
