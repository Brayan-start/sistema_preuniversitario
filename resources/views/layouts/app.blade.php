<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPEA | Sistema de Inscripciones</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    data-page="{{ request()->routeIs('admin.dashboard') ? 'admin-dashboard' : (request()->routeIs('admin.estadisticas.index') ? 'admin-statistics' : (request()->routeIs('admin.auditoria.index') ? 'admin-audit' : (request()->routeIs('admin.aspirantes.search') ? 'admin-search' : ''))) }}">
    <div id="loader" class="app-loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    @if (session('success'))
        <div class="d-none" data-flash-toast data-type="success" data-message="{{ session('success') }}"></div>
    @endif

    @if (session('error'))
        <div class="d-none" data-flash-toast data-type="error" data-message="{{ session('error') }}"></div>
    @endif

    <div class="wrapper d-flex">
        @auth
            <nav id="sidebar" class="animate__animated animate__fadeInLeft">
                <div class="sidebar-header">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_Msk9Kz9Z1z2w2z2_9-Jv6l8B7r9N8V7J7A&s"
                        alt="Logo UPEA" class="img-fluid mb-2" style="max-width: 60px;">
                    <h6 class="text-white mt-2 mb-0">SISTEMA DE INSCRIPCIONES</h6>
                </div>

                <ul class="list-unstyled components mb-0">
                    @if (auth()->user()->role === 'administrador')
                        <li class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}"><a
                                href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                        <li class="{{ Request::is('admin/estadisticas*') ? 'active' : '' }}"><a
                                href="{{ route('admin.estadisticas.index') }}"><i class="fas fa-chart-line"></i>
                                Estadísticas</a></li>
                        <li class="{{ Request::is('admin/cursos*') ? 'active' : '' }}"><a
                                href="{{ route('admin.cursos.index') }}"><i class="fas fa-book-open"></i> Gestión de
                                Cursos</a></li>
                        <li class="{{ Request::is('admin/inscripciones*') ? 'active' : '' }}"><a
                                href="{{ route('admin.inscripciones.index') }}"><i class="fas fa-file-signature"></i>
                                Inscripciones</a></li>
                        <li class="{{ Request::is('admin/aspirantes*') ? 'active' : '' }}"><a
                                href="{{ route('admin.aspirantes.index') }}"><i class="fas fa-users"></i> Aspirantes</a>
                        </li>
                        <li class="{{ Request::is('admin/pagos*') ? 'active' : '' }}"><a
                                href="{{ route('admin.pagos.index') }}"><i class="fas fa-file-invoice-dollar"></i>
                                Verificación de Pagos</a></li>
                        <li class="{{ Request::is('admin/reportes*') ? 'active' : '' }}"><a
                                href="{{ route('admin.reportes.index') }}"><i class="fas fa-chart-pie"></i> Reportes</a>
                        </li>
                        <li class="{{ Request::is('admin/aspirantes/busqueda*') ? 'active' : '' }}"><a
                                href="{{ route('admin.aspirantes.search') }}"><i class="fas fa-search"></i> Búsqueda
                                Avanzada</a></li>
                        <li class="{{ Request::is('admin/auditoria*') ? 'active' : '' }}"><a
                                href="{{ route('admin.auditoria.index') }}"><i class="fas fa-shield-alt"></i> Auditoría</a>
                        </li>
                        <li class="{{ Request::is('perfil') ? 'active' : '' }}"><a href="{{ route('perfil') }}"><i
                                    class="fas fa-user-circle"></i> Mi Perfil</a></li>
                    @else
                        <li class="{{ Request::is('aspirante/dashboard*') ? 'active' : '' }}"><a
                                href="{{ route('aspirante.dashboard') }}"><i class="fas fa-home"></i> Mi Panel</a></li>
                        <li class="{{ Request::is('aspirante/cursos*') ? 'active' : '' }}"><a
                                href="{{ route('aspirante.cursos') }}"><i class="fas fa-graduation-cap"></i> Cursos
                                Disponibles</a></li>
                        <li class="{{ Request::is('aspirante/documentos*') ? 'active' : '' }}"><a
                                href="{{ route('aspirante.documentos') }}"><i class="fas fa-cloud-upload-alt"></i> Mis
                                Documentos</a></li>
                        <li class="{{ Request::is('aspirante/pagos*') ? 'active' : '' }}"><a
                                href="{{ route('aspirante.pagos') }}"><i class="fas fa-receipt"></i> Mis Pagos</a></li>
                        <li class="{{ Request::is('perfil') ? 'active' : '' }}"><a href="{{ route('perfil') }}"><i
                                    class="fas fa-user-circle"></i> Mi Perfil</a></li>
                    @endif
                    <li class="{{ Request::is('configuracion*') ? 'active' : '' }}"><a
                            href="{{ route('configuracion') }}"><i class="fas fa-cog"></i> Configuración</a></li>
                </ul>

                <div class="px-4 mt-5 pb-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100 btn-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        @endauth

        <div id="content">
            @auth
                <nav class="navbar navbar-expand-lg top-navbar animate__animated animate__fadeInDown">
                    <div class="container-fluid px-0">
                        <button type="button" id="sidebarCollapse"
                            class="btn btn-link text-dark p-0 text-decoration-none">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>

                        <div class="ms-auto d-flex align-items-center">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none"
                                    href="#" role="button" data-bs-toggle="dropdown">
                                    <span
                                        class="me-2 d-none d-md-block fw-medium text-dark">{{ auth()->user()->name }}</span>
                                    @if (auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                                            alt="Foto de perfil" class="rounded-circle"
                                            style="width: 36px; height: 36px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                                    <li><a class="dropdown-item" href="{{ route('perfil') }}"><i
                                                class="fas fa-user me-2"></i> Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('configuracion') }}"><i
                                                class="fas fa-cog me-2"></i> Configuración</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i
                                                    class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            @endauth

            <main class="container-fluid p-4" data-aos="fade-up">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>
