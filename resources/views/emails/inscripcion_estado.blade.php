<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #004a99; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .header.aprobado { background: #28a745; }
        .header.rechazado { background: #dc3545; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $inscripcion->estado }}">
            <h1>Estado de Inscripción: {{ strtoupper($inscripcion->estado) }}</h1>
        </div>
        
        <h2>Hola, {{ $inscripcion->aspirante?->nombre_completo ?? 'Aspirante' }}</h2>
        <p>Te informamos sobre el estado de tu inscripción al curso <strong>{{ $inscripcion->curso->nombre_curso }}</strong>.</p>
        
        @if($inscripcion->estado === 'aprobado')
            <div class="details">
                <p><strong>¡Felicidades!</strong> Tu inscripción ha sido aprobada satisfactoriamente.</p>
                <ul>
                    <li><strong>Curso:</strong> {{ $inscripcion->curso->nombre_curso }}</li>
                    <li><strong>Grupo Asignado:</strong> {{ $inscripcion->grupo }}</li>
                    <li><strong>Fecha de Inicio:</strong> {{ $inscripcion->curso->fecha_inicio ? $inscripcion->curso->fecha_inicio->format('d/m/Y') : 'Por definir' }}</li>
                    <li><strong>Horario:</strong> {{ $inscripcion->curso->horario ?? 'Consultar en plataforma' }}</li>
                </ul>
                <p>Te esperamos para iniciar este nuevo ciclo académico.</p>
            </div>
        @elseif($inscripcion->estado === 'rechazado')
            <div class="details">
                <p>Lamentamos informarte que tu inscripción ha sido <strong>rechazada</strong>.</p>
                <p><strong>Motivo del rechazo:</strong></p>
                <p><em>{{ $inscripcion->motivo_rechazo }}</em></p>
                <p><strong>Instrucciones:</strong> Por favor, revisa las observaciones mencionadas arriba. Si el periodo de inscripciones sigue abierto, puedes intentar corregir la información y postularte nuevamente o contactar con soporte técnico.</p>
            </div>
        @else
            <p>Tu inscripción se encuentra actualmente <strong>en revisión</strong>. Te notificaremos pronto sobre cualquier cambio.</p>
        @endif

        <div class="footer">
            <p>Este es un correo automático, por favor no respondas.</p>
            <p>Atentamente,<br>Dirección Académica - UPEA</p>
        </div>
    </div>
</body>
</html>
