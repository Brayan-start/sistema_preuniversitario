<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="{{ $id }}Label">
                    <i class="fas {{ $icon }} me-2" style="color: {{ $iconColor }}"></i>
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-0" style="color: var(--app-text, #27313f);">{{ $message }}</p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> {{ $cancelText ?? 'Cancelar' }}
                </button>
                <button type="button" class="btn {{ $confirmClass }} rounded-pill px-4 shadow-sm" id="{{ $id }}Confirm">
                    <i class="fas {{ $confirmIcon }} me-1"></i> {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
