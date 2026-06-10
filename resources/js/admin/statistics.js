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
                backgroundColor: canvas.dataset.color || 'rgba(180, 35, 24, 0.6)',
                borderColor: canvas.dataset.stroke || 'rgba(180, 35, 24, 1)',
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
    const params = new URLSearchParams(window.location.search);
    const response = await fetch(`/admin/estadisticas/datos?${params.toString()}`, { headers: { Accept: 'application/json' } });

    if (!response.ok) {
        return;
    }

    const payload = await response.json();

    renderChart('statsAspirantesMes', 'line', payload.aspirantes_por_mes, 'Aspirantes por mes');
    renderChart('statsInscripcionesMes', 'line', payload.inscripciones_por_mes, 'Inscripciones por mes');
    renderChart('statsIngresosMes', 'bar', payload.ingresos_por_mes, 'Ingresos por mes');
    renderChart('statsPagosEstado', 'doughnut', payload.pagos_por_estado, 'Pagos por estado');
    renderChart('statsDistribucionEstados', 'bar', payload.distribucion_estados_inscripcion, 'Estados de inscripción');
    renderChart('statsTopCursos', 'bar', payload.top_cursos_mas_inscritos, 'Top cursos');
    renderChart('statsAspirantesCurso', 'bar', payload.aspirantes_por_curso, 'Aspirantes por curso');
    renderChart('statsCursosDemanda', 'bar', payload.cursos_mayor_demanda, 'Cursos con mayor demanda');
    renderChart('statsCrecimientoInscripciones', 'line', payload.crecimiento_inscripciones, 'Crecimiento de inscripciones');
    renderChart('statsComparativaIngresos', 'bar', payload.comparativa_ingresos, 'Comparativa de ingresos');
};

init();
