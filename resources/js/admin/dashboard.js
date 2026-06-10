const renderChart = (canvasId, type, chartData, label) => {
    const canvas = document.getElementById(canvasId);

    if (!canvas || !window.Chart) {
        return;
    }

    const items = chartData ?? [];

    new window.Chart(canvas, {
        type,
        data: {
            labels: items.map((item) => item.label),
            datasets: [{
                label,
                data: items.map((item) => item.value),
                backgroundColor: canvas.dataset.color || 'rgba(16, 58, 92, 0.6)',
                borderColor: canvas.dataset.stroke || 'rgba(16, 58, 92, 1)',
                borderWidth: 2,
                fill: type === 'line',
                tension: 0.35,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
};

const init = async () => {
    const response = await fetch('/admin/dashboard/datos', { headers: { Accept: 'application/json' } });

    if (!response.ok) {
        return;
    }

    const payload = await response.json();

    renderChart('chartAspirantesMes', 'line', payload.estadisticas?.aspirantes_por_mes, 'Aspirantes por mes');
    renderChart('chartInscripcionesMes', 'line', payload.estadisticas?.inscripciones_por_mes, 'Inscripciones por mes');
    renderChart('chartIngresosMes', 'bar', payload.estadisticas?.ingresos_por_mes, 'Ingresos por mes');
    renderChart('chartPagosEstado', 'doughnut', payload.estadisticas?.pagos_por_estado, 'Pagos por estado');
    renderChart('chartAspirantesCurso', 'bar', payload.estadisticas?.aspirantes_por_curso, 'Aspirantes por curso');
};

init();