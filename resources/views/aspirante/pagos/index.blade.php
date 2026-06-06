@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h2 class="fw-bold">Gestión de Pagos</h2>
            <p class="text-muted">Registra tu comprobante de depósito bancario para formalizar tu inscripción.</p>
        </div>

        @if(!$inscripcion)
            <div class="card border-0 shadow-sm text-center p-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="fw-bold">Primero debes inscribirte a un curso</h5>
                <p class="text-muted">No tienes una inscripción activa para registrar un pago.</p>
                <a href="{{ route('aspirante.cursos') }}" class="btn btn-primary rounded-pill px-4 mt-2">Ver Cursos Disponibles</a>
            </div>
        @elseif($inscripcion->pago)
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-success text-white p-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-check-circle me-2"></i> Pago Registrado</h5>
                </div>
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="text-muted small text-uppercase fw-bold">Número de Comprobante</h6>
                            <p class="fs-4 fw-bold text-dark">{{ $inscripcion->pago->numero_comprobante }}</p>
                            
                            <h6 class="text-muted small text-uppercase fw-bold mt-4">Monto Depositado</h6>
                            <p class="fs-4 fw-bold text-primary">Bs. {{ number_format($inscripcion->pago->monto, 2) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted small text-uppercase fw-bold">Estado del Pago</h6>
                            <span class="badge bg-{{ $inscripcion->pago->estado == 'aprobado' ? 'success' : ($inscripcion->pago->estado == 'rechazado' ? 'danger' : 'warning') }} rounded-pill px-4 py-2 fs-6">
                                {{ strtoupper($inscripcion->pago->estado) }}
                            </span>
                            <p class="text-muted small mt-2">Registrado el {{ $inscripcion->pago->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @if($inscripcion->pago->motivo_rechazo)
                        <div class="alert alert-danger border-0 shadow-sm mt-4">
                            <h6 class="fw-bold"><i class="fas fa-times-circle me-2"></i> Motivo de Observación:</h6>
                            <p class="mb-0">{{ $inscripcion->pago->motivo_rechazo }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <h5 class="mb-0 fw-bold">Registro de Comprobante</h5>
                    <small>Depósito Bancario - Banco Unión</small>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('aspirante.pagos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inscripcion_id" value="{{ $inscripcion->id }}">
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-uppercase">Nro. de Comprobante / Transacción</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" name="numero_comprobante" class="form-control" required placeholder="00000000">
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-uppercase">Monto Depositado (Bs.)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><b>Bs.</b></span>
                                    <input type="number" step="0.01" name="monto" class="form-control" required placeholder="500.00">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Fecha del Depósito</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="fecha_pago" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase">Foto del Comprobante (PDF/JPG)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-camera"></i></span>
                                <input type="file" name="comprobante" class="form-control" required>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 shadow-sm small mb-5">
                            <i class="fas fa-info-circle me-2"></i> Asegúrate de que los datos coincidan exactamente con tu comprobante para evitar rechazos.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 rounded-pill shadow">
                                <i class="fas fa-save me-2"></i> Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
