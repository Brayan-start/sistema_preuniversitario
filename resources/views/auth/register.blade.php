@extends('layouts.app')

@section('content')
<style>
    /* Custom Register Styles */
    #content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; background: #f4f7f6; }
    .top-navbar { display: none !important; }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
    }

    .register-card {
        max-width: 800px;
        width: 100%;
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .register-header {
        background: var(--upea-blue);
        color: white;
        padding: 40px;
        text-align: center;
    }

    .register-body {
        padding: 50px;
    }

    .form-section-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--upea-blue);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #eee;
        margin-left: 15px;
    }

    .input-group-text {
        background: #f8f9fa;
        border-right: none;
        color: #666;
    }

    .form-control {
        border-left: none;
        background: #f8f9fa;
    }

    .form-control:focus {
        background: #fff;
        box-shadow: none;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
</style>

<div class="register-wrapper">
    <div class="register-card animate__animated animate__zoomIn">
        <div class="register-header">
            <h2 class="fw-bold mb-2">Crear Nueva Cuenta</h2>
            <p class="opacity-75 mb-0">Únete a la carrera de Ingeniería de Sistemas</p>
        </div>

        <div class="register-body">
            <!-- Simple Progress Indicator -->
            <div class="d-flex justify-content-between mb-2 small fw-bold text-uppercase">
                <span>Progreso del Registro</span>
                <span id="progressPercent">0%</span>
            </div>
            <div class="progress mb-5">
                <div id="registerProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
            </div>

            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf
                
                <!-- Section 1: Personal Data -->
                <div class="form-section-title"><i class="fas fa-user-circle me-2"></i> Datos Personales</div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label small fw-bold">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ old('nombre_completo') }}" required placeholder="Juan Perez">
                        </div>
                        @error('nombre_completo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold">Cédula de Identidad (CI)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci') }}" required placeholder="1234567 LP">
                        </div>
                        @error('ci') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold">Celular</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror" value="{{ old('celular') }}" required placeholder="+591 70000000">
                        </div>
                        @error('celular') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8 mb-4">
                        <label class="form-label small fw-bold">Colegio de Procedencia</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                            <input type="text" name="colegio_procedencia" class="form-control @error('colegio_procedencia') is-invalid @enderror" value="{{ old('colegio_procedencia') }}" required placeholder="Unidad Educativa...">
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label small fw-bold">Año de Egreso</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="number" name="anio_egreso" class="form-control @error('anio_egreso') is-invalid @enderror" value="{{ old('anio_egreso') }}" required placeholder="2024">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Account Access -->
                <div class="form-section-title mt-4"><i class="fas fa-lock me-2"></i> Seguridad de la Cuenta</div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label small fw-bold">Correo Institucional / Personal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required placeholder="aspirante@upea.bo">
                        </div>
                        @error('correo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" name="password" id="regPassword" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label small fw-bold">Confirmar Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill shadow-lg">
                        <i class="fas fa-user-plus me-2"></i> Completar Mi Registro
                    </button>
                    <div class="text-center mt-4">
                        <p class="text-muted small">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Inicia Sesión aquí</a></p>
                        <a href="/" class="small text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Volver al Inicio</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Real-time progress update
    const form = document.getElementById('registerForm');
    const inputs = form.querySelectorAll('input');
    const progressBar = document.getElementById('registerProgress');
    const progressPercent = document.getElementById('progressPercent');

    function updateProgress() {
        let filledCount = 0;
        inputs.forEach(input => {
            if (input.value.trim() !== '') filledCount++;
        });
        const percentage = Math.round((filledCount / inputs.length) * 100);
        progressBar.style.width = percentage + '%';
        progressPercent.innerText = percentage + '%';
    }

    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
    });

    // Initial call
    updateProgress();
</script>
@endsection
