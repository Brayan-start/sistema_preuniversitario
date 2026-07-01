<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPEA | Sistema de Inscripciones</title>
    <script>
        (function () {
            const savedTheme = localStorage.getItem('upea-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
    
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
            --app-bg: #f4f7f6;
            --app-surface: #ffffff;
            --app-surface-soft: #f7f9fc;
            --app-text: #27313f;
            --app-muted: #6c757d;
            --app-border: #e6ebf2;
            --app-sidebar-bg: #003366;
            --app-sidebar-link: rgba(255,255,255,0.82);
            --app-sidebar-hover: rgba(255,255,255,0.12);
            --app-input-bg: #ffffff;
            --app-input-text: #27313f;
            --app-shadow: 0 8px 26px rgba(15, 23, 42, 0.08);
            --app-shadow-soft: 0 4px 16px rgba(15, 23, 42, 0.08);
            --app-transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        :root[data-theme="dark"] {
            --upea-blue: #5b8def;
            --upea-red: #ff6b6b;
            --upea-light: #1f2937;
            --upea-dark: #f8fafc;
            --app-bg: #0f172a;
            --app-surface: #162033;
            --app-surface-soft: #1f2a3d;
            --app-text: #e5edf8;
            --app-muted: #a8b3c4;
            --app-border: #2c3a50;
            --app-sidebar-bg: #0a1222;
            --app-sidebar-link: rgba(229,237,248,0.82);
            --app-sidebar-hover: rgba(91,141,239,0.18);
            --app-input-bg: #101827;
            --app-input-text: #e5edf8;
            --app-shadow: 0 12px 32px rgba(0, 0, 0, 0.34);
            --app-shadow-soft: 0 6px 18px rgba(0, 0, 0, 0.26);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--app-bg);
            color: var(--app-text);
            overflow-x: hidden;
            transition: var(--app-transition);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--app-sidebar-bg);
            transition: all 0.3s;
            position: fixed;
            z-index: 1050;
            box-shadow: var(--app-shadow-soft);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 25px;
            font-size: 0.95rem;
            display: block;
            color: var(--app-sidebar-link);
            text-decoration: none;
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: var(--app-sidebar-hover);
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

        body.sidebar-collapsed #sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        body.sidebar-collapsed #content {
            width: 100%;
            margin-left: 0;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--app-surface);
            padding: 15px 30px;
            box-shadow: var(--app-shadow-soft);
            transition: var(--app-transition);
        }

        .top-navbar .btn-link,
        .top-navbar .nav-link {
            color: var(--app-text) !important;
        }

        .avatar {
            border-radius: 50%;
            object-fit: cover;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-weight: 700;
            background: linear-gradient(135deg, rgba(91, 141, 239, 0.18), rgba(204, 0, 0, 0.12));
            color: var(--upea-blue);
            border: 2px solid var(--app-surface);
            box-shadow: var(--app-shadow-soft);
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

        .user-profile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--app-surface);
            box-shadow: var(--app-shadow-soft);
        }

        .theme-toggle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--app-text);
            background: var(--app-surface-soft);
            border: 1px solid var(--app-border);
            transition: var(--app-transition);
        }

        .theme-toggle:hover {
            color: var(--upea-blue);
            border-color: var(--upea-blue);
            box-shadow: var(--app-shadow-soft);
        }

        .user-dropdown {
            min-width: 280px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--app-surface);
            border-color: var(--app-border) !important;
        }

        .user-dropdown .dropdown-item {
            padding: 10px 16px;
            font-weight: 500;
            color: var(--app-text);
        }

        .user-dropdown .dropdown-item i {
            width: 22px;
            color: var(--upea-blue);
        }

        .user-dropdown .dropdown-header-card {
            background: var(--app-surface-soft);
            padding: 16px;
            border-bottom: 1px solid var(--app-border);
        }

        /* Main Dashboard Cards */
        .card {
            background: var(--app-surface);
            color: var(--app-text);
            border: 1px solid var(--app-border) !important;
            border-radius: 12px;
            box-shadow: var(--app-shadow);
            transition: transform 0.3s, var(--app-transition);
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
            background-color: #174ea6;
            border-color: #174ea6;
        }

        .btn-outline-secondary {
            color: var(--app-text);
            border-color: var(--app-border);
        }

        .btn-outline-secondary:hover {
            color: var(--app-surface);
            background-color: var(--app-text);
            border-color: var(--app-text);
        }

        .text-muted {
            color: var(--app-muted) !important;
        }

        .bg-white,
        .card-header,
        .modal-content,
        .dropdown-menu,
        .list-group-item,
        .accordion-item,
        .accordion-button,
        .table {
            background-color: var(--app-surface) !important;
            color: var(--app-text) !important;
            border-color: var(--app-border) !important;
            transition: var(--app-transition);
        }

        .bg-light,
        .table-light,
        .table thead,
        .dropdown-header-card {
            background-color: var(--app-surface-soft) !important;
            color: var(--app-text) !important;
        }

        .table,
        .table > :not(caption) > * > * {
            color: var(--app-text);
            background-color: transparent;
            border-color: var(--app-border);
        }

        .table-hover > tbody > tr:hover > * {
            background-color: var(--app-surface-soft);
            color: var(--app-text);
        }

        .form-control,
        .form-select,
        .form-check-input {
            background-color: var(--app-input-bg);
            color: var(--app-input-text);
            border-color: var(--app-border);
            transition: var(--app-transition);
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--app-input-bg);
            color: var(--app-input-text);
            border-color: var(--upea-blue);
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.18);
        }

        .modal-backdrop {
            --bs-backdrop-opacity: 0.58;
        }

        .modal-header,
        .modal-footer,
        .dropdown-divider,
        hr {
            border-color: var(--app-border) !important;
        }

        .page-link {
            background-color: var(--app-surface);
            color: var(--app-text);
            border-color: var(--app-border);
        }

        .page-link:hover,
        .active > .page-link,
        .page-link.active {
            background-color: var(--upea-blue);
            border-color: var(--upea-blue);
            color: #fff;
        }

        .alert {
            background-color: var(--app-surface-soft);
            color: var(--app-text);
            border-color: var(--app-border);
        }

        .swal2-popup {
            background: var(--app-surface) !important;
            color: var(--app-text) !important;
        }

        .profile-summary-card {
            min-height: 360px;
        }

        .profile-summary-card .card-body {
            min-height: 360px;
        }

        .profile-photo-lg,
        .profile-avatar-lg {
            width: 104px;
            height: 104px;
            border: 4px solid var(--app-surface);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.16);
        }

        .profile-avatar-lg {
            font-size: 2.4rem;
            background: linear-gradient(135deg, rgba(91, 141, 239, 0.18), rgba(204, 0, 0, 0.12));
            color: var(--upea-blue);
        }

        .profile-role-badge {
            background: rgba(91, 141, 239, 0.14);
            color: var(--upea-blue);
            border: 1px solid rgba(91, 141, 239, 0.28);
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
            background: var(--app-surface);
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

            body.sidebar-open #sidebar {
                margin-left: 0;
            }

            #content {
                width: 100%;
                margin-left: 0;
            }

            body.sidebar-collapsed #content,
            body.sidebar-open #content {
                width: 100%;
                margin-left: 0;
            }
        }

        /* Sidebar Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            -webkit-tap-highlight-color: transparent;
        }

        body.sidebar-open .sidebar-backdrop {
            opacity: 1;
            visibility: visible;
        }

        /* Sidebar Close Button */
        .sidebar-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.15);
            border: none;
            color: #fff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.2s;
            z-index: 1060;
            padding: 0;
            line-height: 1;
        }

        .sidebar-close-btn:hover {
            background: rgba(255,255,255,0.25);
        }

        @media (max-width: 992px) {
            .sidebar-close-btn {
                display: flex;
            }

            body.sidebar-open {
                overflow: hidden;
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
            <div class="sidebar-header" style="position: relative;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_Msk9Kz9Z1z2w2z2_9-Jv6l8B7r9N8V7J7A&s" alt="Logo UPEA" class="img-fluid mb-2" style="max-width: 60px;">
                <h6 class="text-white mt-2">SISTEMA DE INSCRIPCIONES</h6>
                <button type="button" id="sidebarCloseBtn" class="sidebar-close-btn" aria-label="Cerrar menú">
                    <i class="fas fa-times"></i>
                </button>
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
                    <li class="{{ Request::is('admin/documentos*') ? 'active' : '' }}">
                        <a href="{{ route('admin.documentos.index') }}"><i class="fas fa-file-alt"></i> Documentos</a>
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
                <form id="logoutFormSidebar" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-outline-light w-100 btn-sm" onclick="showConfirmModal('logoutModal', function() { document.getElementById('logoutFormSidebar').submit(); })">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
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
                    
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <button type="button" id="themeToggle" class="theme-toggle" aria-label="Cambiar tema" title="Cambiar tema">
                            <i class="fas fa-moon" id="themeToggleIcon"></i>
                        </button>

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <span class="me-2 d-none d-md-block fw-medium">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->profile_photo_path && filter_var(auth()->user()->profile_photo_path, FILTER_VALIDATE_URL))
                                    <img src="{{ auth()->user()->profile_photo_path }}" alt="Foto de perfil" class="user-profile">
                                @else
                                    <div class="user-profile bg-secondary text-white d-flex align-items-center justify-content-center">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                                <li><a class="dropdown-item" href="{{ route('perfil') }}"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('configuracion') }}"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logoutFormTopbar" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="button" class="dropdown-item text-danger" onclick="showConfirmModal('logoutModal', function() { document.getElementById('logoutFormTopbar').submit(); })">
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

    @stack('modals')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        if (window.AOS) {
            AOS.init({
                duration: 800,
                once: true
            });
        }

        const sidebarStorageKey = 'upea-sidebar-state';
        const themeStorageKey = 'upea-theme';

        function applyTheme(theme) {
            const normalizedTheme = theme === 'dark' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', normalizedTheme);
            document.documentElement.setAttribute('data-bs-theme', normalizedTheme);

            const icon = document.getElementById('themeToggleIcon');
            const toggle = document.getElementById('themeToggle');
            if (icon) {
                icon.className = normalizedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
            if (toggle) {
                toggle.setAttribute('aria-label', normalizedTheme === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro');
                toggle.setAttribute('title', normalizedTheme === 'dark' ? 'Tema claro' : 'Tema oscuro');
            }
        }

        function isMobileLayout() {
            return window.matchMedia('(max-width: 992px)').matches;
        }

        function applySidebarState(state) {
            document.body.classList.remove('sidebar-open', 'sidebar-collapsed');

            if (isMobileLayout()) {
                if (state === 'open') {
                    document.body.classList.add('sidebar-open');
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
                return;
            }

            if (state === 'closed') {
                document.body.classList.add('sidebar-collapsed');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebarButton = document.getElementById('sidebarCollapse');
            const themeButton = document.getElementById('themeToggle');
            const defaultState = isMobileLayout() ? 'closed' : 'open';
            const savedState = localStorage.getItem(sidebarStorageKey) || defaultState;

            applyTheme(localStorage.getItem(themeStorageKey) || 'light');
            applySidebarState(savedState);

            if (themeButton) {
                themeButton.addEventListener('click', function () {
                    const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem(themeStorageKey, nextTheme);
                    applyTheme(nextTheme);
                });
            }

            if (sidebarButton) {
                sidebarButton.addEventListener('click', function () {
                    const isOpen = isMobileLayout()
                        ? document.body.classList.contains('sidebar-open')
                        : !document.body.classList.contains('sidebar-collapsed');
                    const nextState = isOpen ? 'closed' : 'open';

                    localStorage.setItem(sidebarStorageKey, nextState);
                    applySidebarState(nextState);
                });
            }

            // Close sidebar via backdrop click
            const backdrop = document.getElementById('sidebarBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    if (isMobileLayout() && document.body.classList.contains('sidebar-open')) {
                        localStorage.setItem(sidebarStorageKey, 'closed');
                        applySidebarState('closed');
                    }
                });
            }

            // Close sidebar via close button
            const closeBtn = document.getElementById('sidebarCloseBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    if (isMobileLayout()) {
                        localStorage.setItem(sidebarStorageKey, 'closed');
                        applySidebarState('closed');
                    }
                });
            }

            // ESC key to close sidebar
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && isMobileLayout() && document.body.classList.contains('sidebar-open')) {
                    localStorage.setItem(sidebarStorageKey, 'closed');
                    applySidebarState('closed');
                }
            });

            window.addEventListener('resize', function () {
                applySidebarState(localStorage.getItem(sidebarStorageKey) || (isMobileLayout() ? 'closed' : 'open'));
            });

            const loader = document.getElementById('loader');
            if (loader) {
                setTimeout(function() {
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 500);
                }, 500);
            }
        });

        const Toast = window.Swal ? Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        }) : null;

        // Confirm Modal System
        var _modalCallbacks = {};

        window.showConfirmModal = function(modalId, callback) {
            var modalEl = document.getElementById(modalId);
            if (!modalEl) return;
            _modalCallbacks[modalId] = callback;
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            var backdropCount = document.querySelectorAll('.modal-backdrop').length;
            console.log('[ConfirmModal] Opening: ' + modalId + ' | Backdrops before: ' + backdropCount);
            modal.show();
        };

        document.addEventListener('hidden.bs.modal', function() {
            var orphaned = document.querySelectorAll('.modal-backdrop');
            if (orphaned.length) {
                console.log('[ConfirmModal] Cleaning ' + orphaned.length + ' orphaned backdrop(s)');
                orphaned.forEach(function(el) { el.remove(); });
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
        });

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[id$="Confirm"]');
            if (!btn) return;
            var modalId = btn.id.replace('Confirm', '');
            console.log('[ConfirmModal] Confirm clicked for: ' + modalId);
            var cb = _modalCallbacks[modalId];
            if (cb) {
                var modalEl = document.getElementById(modalId);
                if (modalEl) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
                cb();
                delete _modalCallbacks[modalId];
            } else {
                console.warn('[ConfirmModal] No callback registered for: ' + modalId);
            }
        });

        // Flash Messages
        @if(session('success'))
            if (Toast) {
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            }
        @endif

        @if(session('error'))
            if (Toast) {
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            }
        @endif
    </script>

    @auth
        <x-confirm-modal
            id="logoutModal"
            title="Cerrar sesión"
            message="¿Estás seguro de que deseas cerrar la sesión actual? Se te redirigirá a la pantalla de inicio de sesión."
            icon="fa-sign-out-alt"
            iconColor="#dc3545"
            confirmText="Sí, cerrar sesión"
            confirmClass="btn-danger"
            confirmIcon="fa-sign-out-alt"
        />
    @endauth

    @stack('scripts')
</body>
</html>
