@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h2 class="fw-bold">Carga de Documentos</h2>
            <p class="text-muted">Adjunta los requisitos obligatorios para validar tu postulación.</p>
        </div>

        @if(!$inscripcion)
            <div class="card border-0 shadow-sm text-center p-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="fw-bold">Primero debes inscribirte a un curso</h5>
                <p class="text-muted">No tienes una inscripción activa para subir documentos.</p>
                <a href="{{ route('aspirante.cursos') }}" class="btn btn-primary rounded-pill px-4 mt-2">Ver Cursos Disponibles</a>
            </div>
        @elseif(count($inscripcion->documentos) >= 3)
            <div class="card border-0 shadow-sm text-center p-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="fw-bold">Documentos Cargados</h5>
                <p class="text-muted">Ya has subido todos los requisitos obligatorios. Están en proceso de revisión.</p>
                <div class="list-group list-group-flush text-start mt-4">
                    @foreach($inscripcion->documentos as $doc)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <span class="fw-medium">{{ strtoupper($doc->tipo) }}</span>
                            </div>
                            <span class="badge bg-{{ $doc->estado == 'aprobado' ? 'success' : ($doc->estado == 'rechazado' ? 'danger' : 'warning') }} rounded-pill px-3">
                                {{ ucfirst($doc->estado) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <h5 class="mb-0 fw-bold">Formulario de Requisitos</h5>
                    <small>Inscripción: {{ $inscripcion->curso->nombre_curso }}</small>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('aspirante.documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inscripcion_id" value="{{ $inscripcion->id }}">
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Cédula de Identidad (PDF/JPG)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="file" name="ci" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Certificado de Bachillerato (PDF/JPG)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                <input type="file" name="certificado_bachillerato" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Fotografía 3x4 fondo azul (JPG/PNG)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                <input type="file" name="fotografia" class="form-control" required>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm small mb-5">
                            <i class="fas fa-info-circle me-2"></i> El tamaño máximo permitido por archivo es de 2MB.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 rounded-pill shadow">
                                <i class="fas fa-cloud-upload-alt me-2"></i> Subir y Guardar Requisitos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
