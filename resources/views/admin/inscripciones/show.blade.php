@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-white shadow-sm rounded-circle me-3">
                <i class="fas fa-arrow-left text-primary"></i>
            </a>
            <h2 class="fw-bold mb-0">Detalle de Inscripción #{{ $inscripcion->id }}</h2>
        </div>
    </div>

    <div class="col-lg-4 mb-4" data-aos="fade-right">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 fw-bold">Información del Aspirante</div>
            <div class="card-body">
                <h5 class="fw-bold mb-1">{{ $inscripcion->aspirante->nombre_completo }}</h5>
                <p class="text-muted small mb-4">{{ $inscripcion->aspirante->correo }}</p>
                
                <div class="mb-3">
                    <label class="small text-muted d-block text-uppercase fw-bold">Cédula</label>
                    <span class="fw-medium">{{ $inscripcion->aspirante->ci }}</span>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block text-uppercase fw-bold">Celular</label>
                    <span class="fw-medium">{{ $inscripcion->aspirante->celular }}</span>
                </div>
                <div class="mb-0">
                    <label class="small text-muted d-block text-uppercase fw-bold">Curso Seleccionado</label>
                    <span class="fw-bold text-primary">{{ $inscripcion->curso->nombre_curso }}</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3 fw-bold">Acción Administrativa</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.inscripciones.validar', $inscripcion->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold">ESTADO DE INSCRIPCIÓN</label>
                        <select name="estado" class="form-select border-0 bg-light" required id="estadoSelect">
                            <option value="pendiente" {{ $inscripcion->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ $inscripcion->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="aprobado" {{ $inscripcion->estado == 'aprobado' ? 'selected' : '' }}>Aprobar</option>
                            <option value="rechazado" {{ $inscripcion->estado == 'rechazado' ? 'selected' : '' }}>Rechazar</option>
                        </select>
                    </div>

                    <div class="mb-4" id="grupoDiv" style="{{ $inscripcion->estado == 'aprobado' ? '' : 'display:none;' }}">
                        <label class="form-label small fw-bold">ASIGNAR GRUPO</label>
                        <input type="text" name="grupo" class="form-control border-0 bg-light" value="{{ $inscripcion->grupo }}" placeholder="Ej: Grupo A">
                    </div>

                    <div class="mb-4" id="motivoDiv" style="{{ $inscripcion->estado == 'rechazado' ? '' : 'display:none;' }}">
                        <label class="form-label small fw-bold text-danger">MOTIVO DE RECHAZO</label>
                        <textarea name="motivo_rechazo" class="form-control border-0 bg-light-subtle border-danger" rows="3">{{ $inscripcion->motivo_rechazo }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill shadow">
                        <i class="fas fa-save me-2"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8" data-aos="fade-left">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 fw-bold">Documentos Adjuntos</div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($inscripcion->documentos as $doc)
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center bg-light">
                                <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                <h6 class="small fw-bold mb-2">{{ strtoupper($doc->tipo) }}</h6>
                                <a href="{{ route('documentos.archivo', $doc->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" target="_blank">Ver Archivo</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted italic">Aún no se han cargado documentos.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 fw-bold">Información de Pago</div>
            <div class="card-body">
                @if($inscripcion->pago)
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm mb-0">
                                <tr><td class="text-muted small fw-bold">Nro. COMPROBANTE:</td><td class="fw-bold">{{ $inscripcion->pago->numero_comprobante }}</td></tr>
                                <tr><td class="text-muted small fw-bold">MONTO PAGADO:</td><td class="fw-bold text-success">Bs. {{ number_format($inscripcion->pago->monto, 2) }}</td></tr>
                                <tr><td class="text-muted small fw-bold">FECHA PAGO:</td><td>{{ $inscripcion->pago->fecha_pago }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('pagos.comprobante', $inscripcion->pago->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3" target="_blank">Ver Comprobante</a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted italic">No se ha registrado ningún pago todavía.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('estadoSelect').addEventListener('change', function() {
        const val = this.value;
        document.getElementById('grupoDiv').style.display = (val === 'aprobado') ? 'block' : 'none';
        document.getElementById('motivoDiv').style.display = (val === 'rechazado') ? 'block' : 'none';
    });
</script>
@endsection
