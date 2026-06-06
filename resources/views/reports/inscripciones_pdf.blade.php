<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Inscripciones</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>UNIVERSIDAD PÚBLICA DE EL ALTO</h2>
        <h3>Carrera Ingeniería de Sistemas</h3>
        <h4>Reporte General de Inscripciones - Curso Preuniversitario</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>CI</th>
                <th>Curso</th>
                <th>Estado</th>
                <th>Grupo</th>
                <th>Monto (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $i)
            <tr>
                <td>{{ $i->aspirante->nombre_completo }}</td>
                <td>{{ $i->aspirante->ci }}</td>
                <td>{{ $i->curso->nombre_curso }}</td>
                <td>{{ ucfirst($i->estado) }}</td>
                <td>{{ $i->grupo ?? '-' }}</td>
                <td>{{ $i->pago ? number_format($i->pago->monto, 2) : '0.00' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Fecha de generación: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
