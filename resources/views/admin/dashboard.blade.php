@extends('layouts.app')

@section('content')
    <div class="dashboard-header p-4 p-lg-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
            <div>
                <span class="badge bg-light text-dark rounded-pill mb-3">Panel Ejecutivo</span>
                <h2 class="fw-bold mb-2">Dashboard Administrativo</h2>
                <p class="mb-0 text-white-50">Indicadores clave, tendencia operativa y actividad reciente del sistema.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="metric-pill"><i class="fas fa-calendar-day text-primary"></i> {{ now()->format('d/m/Y') }}</span>
                <a href="{{ route('admin.estadisticas.index') }}" class="btn btn-light rounded-pill px-4">Ver estadísticas</a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Total Aspirantes" :value="$kpis['total_aspirantes'] ?? 0" icon="fa-users"
                variant="primary" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Total Inscripciones" :value="$kpis['total_inscripciones'] ?? 0"
                icon="fa-file-signature" variant="info" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Cursos Activos" :value="$kpis['total_cursos_activos'] ?? 0" icon="fa-book-open"
                variant="success" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Pagos Realizados" :value="$kpis['total_pagos_realizados'] ?? 0" icon="fa-credit-card"
                variant="warning" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Pagos Pendientes" :value="$kpis['total_pagos_pendientes'] ?? 0" icon="fa-clock"
                variant="danger" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Ingresos Acumulados" :value="'Bs. ' . number_format($kpis['ingresos_acumulados'] ?? 0, 2)" icon="fa-sack-dollar"
                variant="primary" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Ingresos del Mes" :value="'Bs. ' . number_format($kpis['ingresos_mes_actual'] ?? 0, 2)" icon="fa-chart-line"
                variant="success" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Nuevos Aspirantes" :value="$kpis['nuevos_aspirantes_mes'] ?? 0" icon="fa-user-plus"
                variant="info" /></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Pagos Completados" :value="number_format($kpis['porcentaje_pagos_completados'] ?? 0, 2) . '%'" icon="fa-badge-check"
                variant="success" /></div>
        <div class="col-md-6 col-xl-3"><x-admin.kpi-card title="Promedio Inscripciones" :value="number_format($kpis['promedio_inscripciones_por_curso'] ?? 0, 2)"
                icon="fa-scale-balanced" variant="warning" /></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="app-surface p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Aspirantes e Inscripciones</h5>
                        <p class="text-muted small mb-0">Evolución mensual del registro académico.</p>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill">{{ now()->format('M Y') }}</span>
                </div>
                <div class="dashboard-chart">
                    <canvas id="chartAspirantesMes"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="app-surface p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Ingresos y Pagos</h5>
                        <p class="text-muted small mb-0">Tendencia financiera y estado de cobros.</p>
                    </div>
                </div>
                <div class="dashboard-chart">
                    <canvas id="chartIngresosMes"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Pagos por estado</h5>
                <div class="dashboard-chart"><canvas id="chartPagosEstado"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Inscripciones por mes</h5>
                <div class="dashboard-chart"><canvas id="chartInscripcionesMes"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Aspirantes por curso</h5>
                <div class="dashboard-chart"><canvas id="chartAspirantesCurso"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="app-surface h-100">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Últimos Aspirantes</h5>
                        <p class="text-muted small mb-0">Registros recientes en el sistema.</p>
                    </div>
                    <a href="{{ route('admin.aspirantes.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver
                        todos</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>CI</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimos_aspirantes as $aspirante)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $aspirante->nombre_completo }}</td>
                                    <td>{{ $aspirante->ci }}</td>
                                    <td class="text-muted small">{{ $aspirante->created_at?->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Sin registros recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="app-surface h-100">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Inscripciones Recientes</h5>
                        <p class="text-muted small mb-0">Actividad académica más reciente.</p>
                    </div>
                    <a href="{{ route('admin.inscripciones.index') }}"
                        class="btn btn-sm btn-outline-primary rounded-pill">Gestionar</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Aspirante</th>
                                <th>Curso</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimas_inscripciones as $inscripcion)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $inscripcion->aspirante->nombre_completo }}</td>
                                    <td>{{ $inscripcion->curso->nombre_curso }}</td>
                                    <td><span
                                            class="badge rounded-pill bg-secondary-subtle text-secondary">{{ ucfirst($inscripcion->estado) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No hay inscripciones
                                        registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
