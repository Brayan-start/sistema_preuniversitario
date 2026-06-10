<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1,
        .header h2,
        .header h3 {
            margin: 0;
        }

        .meta,
        .summary,
        .interpretation {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .meta td,
        .summary td,
        .summary th,
        .interpretation td {
            border: 1px solid #dbe2ea;
            padding: 8px;
        }

        .summary th {
            background: #f3f6fb;
            text-align: left;
        }

        .section-title {
            margin: 18px 0 8px;
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
        }

        .footer {
            position: fixed;
            bottom: 18px;
            right: 18px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>UNIVERSIDAD PÚBLICA DE EL ALTO</h1>
        <h2>Ingeniería de Sistemas</h2>
        <h3>Reporte Administrativo de Inscripciones</h3>
    </div>

    <table class="meta">
        <tr>
            <td><strong>Generado por:</strong> {{ $generatedBy->name ?? 'Sistema' }}</td>
            <td><strong>Fecha:</strong> {{ $generatedAt->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Desde:</strong> {{ $filters->from ?? '-' }}</td>
            <td><strong>Hasta:</strong> {{ $filters->to ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Resumen Ejecutivo</div>
    <table class="summary">
        <tr>
            <th>Total Aspirantes</th>
            <th>Total Inscripciones</th>
            <th>Pagos Realizados</th>
            <th>Ingresos Acumulados</th>
        </tr>
        <tr>
            <td>{{ $metrics['total_aspirantes'] ?? 0 }}</td>
            <td>{{ $metrics['total_inscripciones'] ?? 0 }}</td>
            <td>{{ $metrics['total_pagos_realizados'] ?? 0 }}</td>
            <td>Bs. {{ number_format($metrics['ingresos_acumulados'] ?? 0, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Interpretación Automática</div>
    <table class="interpretation">
        <tr>
            <td>{{ $interpretations['resumen'] ?? '' }}</td>
        </tr>
        <tr>
            <td>{{ $interpretations['inscripciones'] ?? '' }}</td>
        </tr>
        <tr>
            <td>{{ $interpretations['pagos'] ?? '' }}</td>
        </tr>
    </table>

    <div class="section-title">Inscripciones</div>
    <table class="summary">
        <tr>
            <th>Aspirante</th>
            <th>CI</th>
            <th>Curso</th>
            <th>Estado</th>
            <th>Grupo</th>
            <th>Monto</th>
        </tr>
        @foreach ($inscripciones as $inscripcion)
            <tr>
                <td>{{ $inscripcion->aspirante->nombre_completo }}</td>
                <td>{{ $inscripcion->aspirante->ci }}</td>
                <td>{{ $inscripcion->curso->nombre_curso }}</td>
                <td>{{ ucfirst($inscripcion->estado) }}</td>
                <td>{{ $inscripcion->grupo ?: '-' }}</td>
                <td>{{ $inscripcion->pago ? number_format($inscripcion->pago->monto, 2) : '0.00' }}</td>
            </tr>
        @endforeach
    </table>

    <div class="section-title">Pagos</div>
    <table class="summary">
        <tr>
            <th>Comprobante</th>
            <th>Aspirante</th>
            <th>Monto</th>
            <th>Estado</th>
        </tr>
        @foreach ($pagos as $pago)
            <tr>
                <td>{{ $pago->numero_comprobante }}</td>
                <td>{{ $pago->inscripcion->aspirante->nombre_completo }}</td>
                <td>Bs. {{ number_format($pago->monto, 2) }}</td>
                <td>{{ ucfirst($pago->estado) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="footer">Generado automáticamente por el sistema</div>
</body>

</html>
