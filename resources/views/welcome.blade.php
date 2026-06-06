@extends('layouts.app')

@section('content')
<style>
    /* Custom Welcome Page Styles */
    #content { margin-left: 0 !important; width: 100% !important; }
    .top-navbar { display: none !important; }
    
    .hero-section {
        background: linear-gradient(rgba(0, 51, 102, 0.8), rgba(0, 51, 102, 0.8)), 
                    url('https://images.unsplash.com/photo-1541339907198-e08756ebafe3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 120px 0;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }

    .hero-title { font-size: 3.5rem; margin-bottom: 20px; }
    
    .step-card {
        padding: 30px;
        text-align: center;
        border-radius: 20px;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: 100%;
        transition: 0.3s;
    }

    .step-card:hover { transform: translateY(-10px); }
    
    .step-icon {
        width: 70px;
        height: 70px;
        line-height: 70px;
        background: var(--upea-blue);
        color: white;
        border-radius: 50%;
        font-size: 1.5rem;
        margin: 0 auto 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        padding-bottom: 15px;
    }

    .section-title::after {
        content: '';
        width: 60px;
        height: 4px;
        background: var(--upea-red);
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .course-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
    }

    .course-card img { height: 200px; object-fit: cover; }
    
    .faq-accordion .accordion-item {
        border: none;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-radius: 10px !important;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: var(--upea-blue);
        color: white;
    }

    footer {
        background: var(--upea-dark);
        color: white;
        padding: 60px 0 20px;
    }
</style>

<!-- Navbar Special for Landing -->
<nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="z-index: 100;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">UPEA - SISTEMA</a>
        <div class="ms-auto d-flex align-items-center">
            @auth
                <a href="{{ auth()->user()->role == 'administrador' ? route('admin.dashboard') : route('aspirante.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">Ir a mi Panel</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-link text-white text-decoration-none me-3">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-4">Registrarse</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container" data-aos="zoom-in">
        <h1 class="hero-title fw-bold">Preuniversitario 2026</h1>
        <p class="lead mb-5 fs-4">Forma parte de la mejor comunidad académica de Ingeniería de Sistemas.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#cursos" class="btn btn-light btn-lg rounded-pill px-5">Ver Cursos</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-5">Postularme</a>
        </div>
    </div>
</section>

<!-- Steps Section -->
<section class="py-5 my-5">
    <div class="container">
        <h2 class="section-title">Proceso de Inscripción</h2>
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                    <h4>1. Registro</h4>
                    <p class="text-muted">Crea tu cuenta de aspirante con tus datos personales.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="step-icon"><i class="fas fa-book"></i></div>
                    <h4>2. Selección</h4>
                    <p class="text-muted">Elige el curso preuniversitario de tu interés.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="step-icon"><i class="fas fa-file-upload"></i></div>
                    <h4>3. Requisitos</h4>
                    <p class="text-muted">Sube tus documentos y registra tu comprobante de pago.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="step-card">
                    <div class="step-icon"><i class="fas fa-check-double"></i></div>
                    <h4>4. Aprobación</h4>
                    <p class="text-muted">¡Listo! Una vez validado, ya eres parte de la carrera.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section id="cursos" class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Cursos Disponibles</h2>
        <div class="row g-4 justify-content-center">
            @forelse($cursos as $curso)
            <div class="col-md-4" data-aos="fade-up">
                <div class="card h-100 course-card">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="card-img-top" alt="Estudiantes">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary">{{ $curso->nombre_curso }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($curso->descripcion, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold fs-5 text-dark">Bs. {{ number_format($curso->monto_arancel, 0) }}</span>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-4">Más Info</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">Próximamente nuevas convocatorias.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 my-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="section-title text-start">Preguntas Frecuentes</h2>
                <div class="accordion faq-accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Cuáles son los requisitos de ingreso?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Debes contar con tu CI original, certificado de bachillerato y el comprobante de depósito bancario correspondiente.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Dónde se realiza el pago del arancel?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                El pago se realiza en las cuentas autorizadas del Banco Unión a nombre de la Universidad Pública de El Alto.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Soporte" class="img-fluid rounded-circle shadow-lg" style="max-width: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container text-center">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_Msk9Kz9Z1z2w2z2_9-Jv6l8B7r9N8V7J7A&s" alt="Logo" style="max-width: 80px;" class="mb-3">
        <h4 class="fw-bold">INGENIERÍA DE SISTEMAS</h4>
        <p class="text-muted mb-4">Universidad Pública de El Alto - Gestión 2026</p>
        <div class="d-flex justify-content-center gap-4 mb-4">
            <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-white fs-4"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
        </div>
        <hr class="bg-secondary">
        <p class="small text-muted">&copy; 2026 Todos los derechos reservados.</p>
    </div>
</footer>

@endsection
