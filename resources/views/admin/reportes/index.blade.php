@extends('layouts.app')

@section('content')
    <div class="dashboard-header p-4 p-lg-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
            <div>
                <span class="badge bg-light text-dark rounded-pill mb-3">Reportes Ejecutivos</span>
                <h2 class="fw-bold mb-2">Reportes y Exportaciones</h2>
                <p class="mb-0 text-white-50">PDF listos para impresión y exportación Excel con hojas consolidadas.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.estadisticas.index') }}" class="btn btn-light rounded-pill px-4">Ver estadísticas</a>
            </div>
        </div>
    </div>

    <div class="app-surface p-4 mb-4">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.reportes.index') }}">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Fecha desde</label>
                <input type="date" name="fecha_desde" class="form-control"
                    value="{{ request('fecha_desde', $filters['fecha_desde'] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Fecha hasta</label>
                <input type="date" name="fecha_hasta" class="form-control"
                    value="{{ request('fecha_hasta', $filters['fecha_hasta'] ?? '') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4">Aplicar</button>
                <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="app-surface h-100 p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="kpi-icon bg-danger-subtle text-danger"><i class="fas fa-file-pdf"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1">Reporte PDF administrativo</h5>
                        <p class="text-muted small mb-0">Resumen ejecutivo, indicadores y tablas consolidadas.</p>
                    </div>
                </div>
                <ul class="text-muted small mb-4">
                    <li>Encabezado institucional y usuario generador.</li>
                    <li>Filtros aplicados y fecha de generación.</li>
                    <li>Indicadores principales e interpretación automática.</li>
                </ul>
                <a href="{{ route('admin.reportes.exportar-pdf', request()->only(['fecha_desde', 'fecha_hasta'])) }}" class="btn btn-danger rounded-pill px-4">
                    <i class="fas fa-download me-2"></i> Descargar PDF
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface h-100 p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="kpi-icon bg-success-subtle text-success"><i class="fas fa-file-excel"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1">Exportación Excel consolidada</h5>
                        <p class="text-muted small mb-0">Hojas separadas de aspirantes, inscripciones, pagos, cursos y
                            estadísticas.</p>
                    </div>
                </div>
                <ul class="text-muted small mb-4">
                    <li>Datos exactos desde la base de datos.</li>
                    <li>Formato tabular con totales y encabezados.</li>
                    <li>Listo para análisis externo.</li>
                </ul>
                <a href="{{ route('admin.reportes.exportar-excel', request()->only(['fecha_desde', 'fecha_hasta'])) }}" class="btn btn-success rounded-pill px-4">
                    <i class="fas fa-file-export me-2"></i> Exportar Excel
                </a>
            </div>
        </div>
    </div>

    <div class="report-summary p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
            <div>
                <h6 class="fw-bold mb-1">Filtros preconfigurados</h6>
                <p class="text-muted small mb-0">Periodo activo:
                    {{ $filters['fecha_desde'] ?? now()->startOfMonth()->toDateString() }} a
                    {{ $filters['fecha_hasta'] ?? now()->endOfMonth()->toDateString() }}</p>
            </div>
            <span class="metric-pill"><i class="fas fa-clock text-primary"></i> Reportes en tiempo real</span>
        </div>
    </div>
@endsection
