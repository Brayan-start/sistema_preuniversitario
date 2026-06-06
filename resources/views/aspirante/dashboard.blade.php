@extends('layouts.app')

@section('content')
<style>
    /* Stepper Styling */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        z-index: 2;
    }

    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 3px solid #ccc;
        width: 100%;
        top: 20px;
        left: -50%;
        z-index: -1;
    }

    .stepper-item::after {
        position: absolute;
        content: "";
        border-bottom: 3px solid #ccc;
        width: 100%;
        top: 20px;
        left: 50%;
        z-index: -1;
    }

    .stepper-item .step-counter {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #ccc;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stepper-item.completed .step-counter { background-color: #28a745; }
    .stepper-item.completed::after { border-bottom-color: #28a745; }
    .stepper-item.completed::before { border-bottom-color: #28a745; }
    
    .stepper-item.active .step-counter { background-color: var(--upea-blue); }
    
    .stepper-item:first-child::before { display: none; }
    .stepper-item:last-child::after { display: none; }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
</style>

<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="fw-bold">Mi Proceso de Admisión</h2>
        <p class="text-muted">Sigue los pasos para completar tu inscripción al curso preuniversitario.</p>
    </div>

    <!-- Enrollment Stepper -->
    <div class="col-md-12 mb-5" data-aos="fade-up">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="stepper-wrapper">
                    @php
                        $step = 1;
                        if($inscripcion) $step = 2;
                        if($inscripcion && count($inscripcion->documentos) >= 3) $step = 3;
                        if($inscripcion && $inscripcion->pago) $step = 4;
                        if($inscripcion && $inscripcion->estado === 'aprobado') $step = 5;
                    @endphp

                    <div class="stepper-item {{ $step >= 1 ? 'completed' : '' }} {{ $step == 1 ? 'active' : '' }}">
                        <div class="step-counter"><i class="fas fa-user-check"></i></div>
                        <div class="step-name small fw-bold">Registro</div>
                    </div>
                    <div class="stepper-item {{ $step >= 2 ? 'completed' : '' }} {{ $step == 2 ? 'active' : '' }}">
                        <div class="step-counter"><i class="fas fa-book"></i></div>
                        <div class="step-name small fw-bold">Inscripción</div>
                    </div>
                    <div class="stepper-item {{ $step >= 3 ? 'completed' : '' }} {{ $step == 3 ? 'active' : '' }}">
                        <div class="step-counter"><i class="fas fa-file-alt"></i></div>
                        <div class="step-name small fw-bold">Documentos</div>
                    </div>
                    <div class="stepper-item {{ $step >= 4 ? 'completed' : '' }} {{ $step == 4 ? 'active' : '' }}">
                        <div class="step-counter"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="step-name small fw-bold">Pago</div>
                    </div>
                    <div class="stepper-item {{ $step >= 5 ? 'completed' : '' }} {{ $step == 5 ? 'active' : '' }}">
                        <div class="step-counter"><i class="fas fa-graduation-cap"></i></div>
                        <div class="step-name small fw-bold">Aprobado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="col-lg-4 mb-4" data-aos="fade-right">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i> Mi Inscripción</h6>
            </div>
            <div class="card-body">
                @if($inscripcion)
                    <h5 class="fw-bold text-primary mb-3">{{ $inscripcion->curso->nombre_curso }}</h5>
                    <div class="mb-3">
                        <label class="small text-muted d-block">Estado de Solicitud</label>
                        @php
                            $statusColor = match($inscripcion->estado) {
                                'aprobado' => 'success',
                                'rechazado' => 'danger',
                                'en_revision' => 'warning',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} border-0 rounded-pill px-3 py-2">
                            {{ strtoupper($inscripcion->estado) }}
                        </span>
                    </div>
                    <div class="mb-0">
                        <label class="small text-muted d-block">Fecha de Solicitud</label>
                        <span class="fw-medium">{{ $inscripcion->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <p class="fw-bold">Aún no estás inscrito</p>
                        <a href="{{ route('aspirante.cursos') }}" class="btn btn-primary btn-sm rounded-pill px-4">Inscribirme ahora</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4" data-aos="fade-left">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-tasks me-2 text-primary"></i> Próximos Pasos</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @if(!$inscripcion)
                        <div class="list-group-item p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 45px; height: 45px; flex-shrink: 0;">1</div>
                                <div>
                                    <h6 class="fw-bold mb-1">Selecciona un curso disponible</h6>
                                    <p class="text-muted small mb-2">Para iniciar tu proceso, debes elegir el curso preuniversitario al que deseas postular.</p>
                                    <a href="{{ route('aspirante.cursos') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver cursos disponibles</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Step 1: Documents -->
                        <div class="list-group-item p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-4 {{ count($inscripcion->documentos) >= 3 ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 45px; height: 45px; flex-shrink: 0;">
                                    @if(count($inscripcion->documentos) >= 3) <i class="fas fa-check"></i> @else 1 @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Carga de Documentos Obligatorios</h6>
                                    <p class="text-muted small mb-0">Debes subir CI, Certificado de Bachillerato y Fotografía.</p>
                                </div>
                                @if(count($inscripcion->documentos) < 3)
                                    <a href="{{ route('aspirante.documentos') }}" class="btn btn-sm btn-primary rounded-pill px-3">Subir Archivos</a>
                                @endif
                            </div>
                        </div>

                        <!-- Step 2: Payment -->
                        <div class="list-group-item p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-4 {{ $inscripcion->pago ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 45px; height: 45px; flex-shrink: 0;">
                                    @if($inscripcion->pago) <i class="fas fa-check"></i> @else 2 @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Registro de Comprobante de Pago</h6>
                                    <p class="text-muted small mb-0">Registra el depósito bancario de Bs. 500 realizado en el Banco Unión.</p>
                                </div>
                                @if(!$inscripcion->pago)
                                    <a href="{{ route('aspirante.pagos') }}" class="btn btn-sm btn-primary rounded-pill px-3">Registrar Pago</a>
                                @endif
                            </div>
                        </div>

                        <!-- Step 3: Verification -->
                        <div class="list-group-item p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-4 {{ $inscripcion->estado === 'aprobado' ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 45px; height: 45px; flex-shrink: 0;">
                                    @if($inscripcion->estado === 'aprobado') <i class="fas fa-check"></i> @else 3 @endif
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Verificación y Asignación de Grupo</h6>
                                    <p class="text-muted small mb-0">Una vez revisado todo, se te asignará un grupo y horario de clases.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
