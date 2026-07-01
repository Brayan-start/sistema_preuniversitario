@extends('layouts.app')

@section('content')
    <div class="dashboard-header p-4 p-lg-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
            <div>
                <span class="badge bg-light text-dark rounded-pill mb-3">Estadísticas</span>
                <h2 class="fw-bold mb-2">Módulo Estadístico</h2>
                <p class="mb-0 text-white-50">Indicadores gráficos consumidos desde endpoints específicos.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.reportes.index') }}" class="btn btn-light rounded-pill px-4">Ir a reportes</a>
            </div>
        </div>
    </div>

    <div class="app-surface p-4 mb-4">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.estadisticas.index') }}">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Fecha desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-uppercase">Fecha hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4">Filtrar</button>
                <a href="{{ route('admin.estadisticas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Aspirantes por mes</h5>
                <div class="dashboard-chart"><canvas id="statsAspirantesMes"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Inscripciones por mes</h5>
                <div class="dashboard-chart"><canvas id="statsInscripcionesMes"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Ingresos por mes</h5>
                <div class="dashboard-chart"><canvas id="statsIngresosMes"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Pagos por estado</h5>
                <div class="dashboard-chart"><canvas id="statsPagosEstado"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Estados de inscripción</h5>
                <div class="dashboard-chart"><canvas id="statsDistribucionEstados"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Top cursos más inscritos</h5>
                <div class="dashboard-chart"><canvas id="statsTopCursos"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Aspirantes por curso</h5>
                <div class="dashboard-chart"><canvas id="statsAspirantesCurso"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Cursos con mayor demanda</h5>
                <div class="dashboard-chart"><canvas id="statsCursosDemanda"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Crecimiento de inscripciones</h5>
                <div class="dashboard-chart"><canvas id="statsCrecimientoInscripciones"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="app-surface p-4 h-100">
                <h5 class="fw-bold mb-3">Comparativa mensual de ingresos</h5>
                <div class="dashboard-chart"><canvas id="statsComparativaIngresos"></canvas></div>
            </div>
        </div>
    </div>
@endsection
