<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPEA | Sistema de Inscripciones</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Custom CSS -->
    <style>
        :root {
            --upea-blue: #003366;
            --upea-red: #cc0000;
            --upea-light: #f8f9fa;
            --upea-dark: #1a1a1a;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--upea-blue);
            transition: all 0.3s;
            position: fixed;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 25px;
            font-size: 0.95rem;
            display: block;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--upea-red);
        }

        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Content Area */
        #content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .avatar {
            border-radius: 50%;
            object-fit: cover;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
            background: linear-gradient(135deg, #e8f0ff, #fff3f3);
            color: var(--upea-blue);
            border: 2px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .avatar-sm {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }

        .avatar-md {
            width: 96px;
            height: 96px;
            font-size: 2.4rem;
        }

        .avatar-lg {
            width: 112px;
            height: 112px;
            font-size: 2.8rem;
        }

        .avatar-upload {
            position: relative;
            display: inline-block;
        }

        .avatar-upload .avatar-action {
            position: absolute;
            right: 2px;
            bottom: 2px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--upea-blue);
            color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.18);
        }

        .user-menu-toggle {
            border-radius: 999px;
            padding: 6px 10px 6px 6px;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .user-menu-toggle:hover {
            background: #f3f6fb;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .user-dropdown {
            min-width: 280px;
            border-radius: 16px;
            overflow: hidden;
        }

        .user-dropdown .dropdown-item {
            padding: 10px 16px;
            font-weight: 500;
        }

        .user-dropdown .dropdown-item i {
            width: 22px;
            color: var(--upea-blue);
        }

        .user-dropdown .dropdown-header-card {
            background: #f7f9fc;
            padding: 16px;
            border-bottom: 1px solid #eef1f5;
        }

        /* Main Dashboard Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Custom Buttons */
        .btn-primary {
            background-color: var(--upea-blue);
            border-color: var(--upea-blue);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #002244;
            border-color: #002244;
        }

        /* Alerts & Notifications */
        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 15px !important;
        }

        .swal2-confirm.btn-primary,
        .swal2-cancel.btn-outline-secondary {
            border-radius: 999px !important;
            padding: 10px 22px !important;
            font-weight: 600 !important;
        }

        /* Page Loader */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--upea-light);
            border-top: 5px solid var(--upea-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Sidebar */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #content.active {
                width: calc(100% - var(--sidebar-width));
                margin-left: var(--sidebar-width);
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Page Loader -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <div class="wrapper d-flex">
        @auth
        <!-- Sidebar -->
        <nav id="sidebar" class="animate__animated animate__fadeInLeft">
            <div class="sidebar-header">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_Msk9Kz9Z1z2w2z2_9-Jv6l8B7r9N8V7J7A&s" alt="Logo UPEA" class="img-fluid mb-2" style="max-width: 60px;">
                <h6 class="text-white mt-2">SISTEMA DE INSCRIPCIONES</h6>
            </div>

            <ul class="list-unstyled components">
                @if(auth()->user()->role === 'administrador')
                    <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a>
                    </li>
                    <li class="{{ Request::is('admin/cursos*') ? 'active' : '' }}">
                        <a href="{{ route('admin.cursos.index') }}"><i class="fas fa-book-open"></i> Gestión de Cursos</a>
                    </li>
                    <li class="{{ Request::is('admin/inscripciones*') ? 'active' : '' }}">
                        <a href="{{ route('admin.inscripciones.index') }}"><i class="fas fa-file-signature"></i> Inscripciones</a>
                    </li>
                    <li class="{{ Request::is('admin/aspirantes*') ? 'active' : '' }}">
                        <a href="{{ route('admin.aspirantes.index') }}"><i class="fas fa-users"></i> Aspirantes</a>
                    </li>
                    <li class="{{ Request::is('admin/pagos*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pagos.index') }}"><i class="fas fa-file-invoice-dollar"></i> Verificación de Pagos</a>
                    </li>
                    <li class="{{ Request::is('admin/reportes*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reportes.index') }}"><i class="fas fa-chart-pie"></i> Reportes</a>
                    </li>
                    <li class="{{ Request::is('admin/auditoria*') ? 'active' : '' }}">
                        <a href="{{ route('admin.auditoria.index') }}"><i class="fas fa-shield-alt"></i> Auditoría</a>
                    </li>
                    <li class="{{ Request::is('perfil') ? 'active' : '' }}">
                        <a href="{{ route('perfil') }}"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                    </li>
                @else
                    <li class="{{ Request::is('aspirante/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('aspirante.dashboard') }}"><i class="fas fa-home"></i> Mi Panel</a>
                    </li>
                    <li class="{{ Request::is('aspirante/cursos*') ? 'active' : '' }}">
                        <a href="{{ route('aspirante.cursos') }}"><i class="fas fa-graduation-cap"></i> Cursos Disponibles</a>
                    </li>
                    <li class="{{ Request::is('aspirante/documentos*') ? 'active' : '' }}">
                        <a href="{{ route('aspirante.documentos') }}"><i class="fas fa-cloud-upload-alt"></i> Mis Documentos</a>
                    </li>
                    <li class="{{ Request::is('aspirante/pagos*') ? 'active' : '' }}">
                        <a href="{{ route('aspirante.pagos') }}"><i class="fas fa-receipt"></i> Mis Pagos</a>
                    </li>
                    <li class="{{ Request::is('perfil') ? 'active' : '' }}">
                        <a href="{{ route('perfil') }}"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                    </li>
                @endif
                <li class="{{ Request::is('configuracion*') ? 'active' : '' }}">
                    <a href="{{ route('configuracion') }}"><i class="fas fa-cog"></i> Configuración</a>
                </li>
            </ul>

            <div class="px-4 mt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
        @endauth

        <!-- Page Content -->
        <div id="content">
            @auth
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg top-navbar animate__animated animate__fadeInDown">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-link text-dark p-0">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="me-2 d-none d-md-block fw-medium">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" alt="Foto de perfil" class="user-profile">
                                @else
                                    <div class="user-profile bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 50%;">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                                <li><a class="dropdown-item" href="{{ route('perfil') }}"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('configuracion') }}"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            @endauth

            <div class="container-fluid p-4" data-aos="fade-up">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Sidebar Toggle
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            // Hide Loader
            setTimeout(function() {
                $('#loader').css('opacity', '0');
                setTimeout(function() {
                    $('#loader').hide();
                }, 500);
            }, 500);
        });

        // Toast Helper
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Flash Messages
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif
    </script>
</body>
</html>
