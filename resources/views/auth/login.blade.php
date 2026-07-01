@extends('layouts.app')

@section('content')
<style>
    /* Custom Login Styles */
    #content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    .top-navbar { display: none !important; }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: stretch;
    }

    .login-image {
        flex: 1;
        background: linear-gradient(rgba(0, 51, 102, 0.7), rgba(0, 51, 102, 0.7)), 
                    url('https://images.unsplash.com/photo-1523050335392-93851179ae22?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        color: white;
    }

    .login-form-side {
        width: 500px;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        box-shadow: -10px 0 30px rgba(0,0,0,0.05);
    }

    .glass-form {
        padding: 20px;
    }

    .input-group-text {
        background-color: transparent;
        border-right: none;
        color: var(--upea-blue);
    }

    .form-control {
        border-left: none;
        padding: 12px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }

    @media (max-width: 992px) {
        .login-image { display: none; }
        .login-form-side { width: 100%; }
    }
</style>

<div class="login-container">
    <!-- Left Side: Image & Text -->
    <div class="login-image animate__animated animate__fadeIn">
        <div data-aos="fade-right">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_Msk9Kz9Z1z2w2z2_9-Jv6l8B7r9N8V7J7A&s" alt="Logo UPEA" style="max-width: 100px;" class="mb-4">
            <h1 class="display-4 fw-bold mb-3">Tu Futuro Comienza Aquí</h1>
            <p class="fs-5 opacity-75">Bienvenido al Sistema de Inscripciones de Ingeniería de Sistemas. Gestiona tu proceso académico de forma rápida y segura.</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="login-form-side animate__animated animate__slideInRight">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Iniciar Sesión</h2>
            <p class="text-muted">Ingresa tus credenciales para continuar</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="glass-form" data-aos="fade-up" data-aos-delay="200">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-medium small text-uppercase">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="ejemplo@upea.bo" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-medium small text-uppercase">Contraseña</label>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword()">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label ms-2 small text-muted" for="remember">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow mb-4">
                <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
            </button>

            <div class="text-center">
                <p class="text-muted small">¿No tienes cuenta? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Regístrate ahora</a></p>
                <a href="/" class="small text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Volver al Inicio</a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('passwordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection
