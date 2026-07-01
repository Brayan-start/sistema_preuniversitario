@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold">Resumen del Sistema</h2>
        <p class="text-muted">Bienvenido al panel de control administrativo.</p>
    </div>

    <!-- KPIs Row -->
    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #007bff;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Aspirantes</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['total_aspirantes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #6c757d;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-secondary-subtle text-secondary p-3 rounded-circle me-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Cursos</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['total_cursos'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #ffc107;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning-subtle text-warning p-3 rounded-circle me-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Pendientes</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['inscripciones_pendientes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #28a745;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success p-3 rounded-circle me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Aprobadas</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['inscripciones_aprobadas'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #fd7e14;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning-subtle text-warning p-3 rounded-circle me-3">
                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Pagos Pendientes</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['pagos_pendientes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="600">
        <div class="card h-100 border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="border-left: 5px solid #17a2b8;">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info-subtle text-info p-3 rounded-circle me-3">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Docs. Pendientes</h6>
                        <h3 class="fw-bold mb-0">{{ $kpis['documentos_pendientes'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Left Column: Recent Registrations -->
    <div class="col-lg-6 mb-4" data-aos="fade-right">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Últimos Aspirantes</h5>
                <a href="{{ route('admin.aspirantes.index') }}" class="btn btn-sm btn-link text-decoration-none">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-muted">
                                <th class="ps-4">Aspirante</th>
                                <th>Cédula</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimos_aspirantes as $a)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            {{ substr($a->nombre_completo, 0, 1) }}
                                        </div>
                                        <div class="fw-medium">{{ $a->nombre_completo }}</div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark fw-normal px-2">{{ $a->ci }}</span></td>
                                <td class="text-muted small">{{ $a->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">Sin registros recientes</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Inscriptions -->
    <div class="col-lg-6 mb-4" data-aos="fade-left">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Inscripciones Recientes</h5>
                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-sm btn-link text-decoration-none">Gestionar</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-uppercase fw-bold text-muted">
                                <th class="ps-4">Aspirante</th>
                                <th>Curso</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimas_inscripciones as $i)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium">{{ optional($i->aspirante)->nombre_completo ?? 'Aspirante eliminado' }}</div>
                                </td>
                                <td><span class="small fw-medium">{{ $i->curso->nombre_curso }}</span></td>
                                <td>
                                    @php
                                        $badgeClass = match($i->estado) {
                                            'aprobado' => 'bg-success-subtle text-success',
                                            'rechazado' => 'bg-danger-subtle text-danger',
                                            'pendiente' => 'bg-warning-subtle text-warning',
                                            default => 'bg-secondary-subtle text-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} border-0 px-3 py-2 rounded-pill small">
                                        {{ ucfirst($i->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No hay inscripciones registradas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
