@props(['title', 'value', 'icon' => 'fa-circle-info', 'variant' => 'primary', 'subtitle' => null])

<div class="card h-100 border-0 shadow-sm kpi-card kpi-card-{{ $variant }}">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-{{ $variant }}-subtle text-{{ $variant }}">
                <i class="fas {{ $icon }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="kpi-label text-uppercase small fw-semibold text-muted">{{ $title }}</div>
                <div class="kpi-value">{{ $value }}</div>
                @if ($subtitle)
                    <div class="kpi-subtitle text-muted small mt-1">{{ $subtitle }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
