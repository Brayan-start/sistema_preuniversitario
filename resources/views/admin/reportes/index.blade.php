@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Reportes y Estadísticas</h2>
    <p class="text-muted">Genera documentos PDF y Excel con la información del sistema.</p>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="fas fa-file-pdf fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Reporte de Inscripciones (PDF)</h5>
                </div>
                <p class="text-muted small">Genera un listado completo de todos los aspirantes inscritos, sus cursos y estados actuales en formato PDF listo para imprimir.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.reportes.exportar-pdf') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-download me-2"></i> Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle text-success p-3 rounded-circle me-3">
                        <i class="fas fa-file-excel fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Reporte Consolidado (Excel)</h5>
                </div>
                <p class="text-muted small">Exporta la base de datos de inscripciones a una hoja de cálculo Excel para realizar análisis avanzado de datos y filtros externos.</p>
                <div class="mt-4">
                    <a href="{{ route('admin.reportes.exportar-excel') }}" class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-file-export me-2"></i> Exportar a Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm mt-4">
    <i class="fas fa-info-circle me-2"></i> Los reportes se generan en tiempo real con la información más reciente de la base de datos.
</div>
@endsection
