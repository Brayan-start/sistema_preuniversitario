@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Cursos Disponibles</h2>
    <p class="text-muted">Selecciona el curso preuniversitario al que deseas postularte.</p>
</div>

<div class="row g-4">
    @forelse($cursos as $curso)
    <div class="col-md-4" data-aos="fade-up">
        <div class="card h-100 border-0 shadow-sm course-card overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 small fw-bold text-uppercase">Convocatoria Abierta</span>
                    <div class="text-end">
                        <small class="text-muted d-block">Cupos</small>
                        <span class="fw-bold">{{ $curso->cupos_disponibles }}</span>
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-3">{{ $curso->nombre_curso }}</h4>
                <p class="text-muted small mb-4">{{ Str::limit($curso->descripcion, 120) }}</p>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <span class="small">Inicia: <b>{{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}</b></span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        <span class="small">Arancel: <b>Bs. {{ number_format($curso->monto_arancel, 2) }}</b></span>
                    </div>
                </div>

                <form action="{{ route('aspirante.inscribirse') }}" method="POST" id="inscripcionForm{{ $curso->id }}">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                    <button
                        type="button"
                        class="btn btn-primary w-100 rounded-pill py-2 shadow-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmarInscripcionModal"
                        data-form-id="inscripcionForm{{ $curso->id }}"
                        data-curso="{{ $curso->nombre_curso }}"
                    >
                        <i class="fas fa-user-plus me-2"></i> Postularme Ahora
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/2618/2618245.png" alt="No cursos" style="max-width: 100px; opacity: 0.3;" class="mb-3 d-block mx-auto">
        <p class="text-muted">Actualmente no existen cursos con cupos disponibles.</p>
    </div>
    @endforelse
</div>

@endsection

@push('modals')
<div class="modal fade" id="confirmarInscripcionModal" tabindex="-1" aria-labelledby="confirmarInscripcionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="confirmarInscripcionLabel">Confirmar inscripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">¿Está seguro de inscribirse a este curso?</p>
                <p class="text-muted small mb-0" id="cursoSeleccionadoTexto"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="confirmarInscripcionBtn">
                    Confirmar inscripción
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirmarInscripcionModal');
        const confirmButton = document.getElementById('confirmarInscripcionBtn');
        const courseText = document.getElementById('cursoSeleccionadoTexto');
        let selectedFormId = null;

        if (!modal || !confirmButton) {
            return;
        }

        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            selectedFormId = button.getAttribute('data-form-id');
            courseText.textContent = button.getAttribute('data-curso') || '';
        });

        modal.addEventListener('hidden.bs.modal', function () {
            selectedFormId = null;
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');

            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop, index) {
                if (index > 0 || !document.querySelector('.modal.show')) {
                    backdrop.remove();
                }
            });
        });

        confirmButton.addEventListener('click', function () {
            if (selectedFormId) {
                confirmButton.disabled = true;
                document.getElementById(selectedFormId).submit();
            }
        });
    });
</script>
@endpush
