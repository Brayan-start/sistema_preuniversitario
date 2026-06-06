<!DOCTYPE html>
<html>
<body>
    <h2>Hola, {{ $inscripcion->aspirante->nombre_completo }}</h2>
    <p>Te informamos que el estado de tu inscripción al curso <strong>{{ $inscripcion->curso->nombre_curso }}</strong> ha cambiado a: <strong>{{ strtoupper($inscripcion->estado) }}</strong>.</p>
    
    @if($inscripcion->estado === 'aprobado')
        <p>¡Felicidades! Tu inscripción ha sido aprobada. Has sido asignado al <strong>Grupo {{ $inscripcion->grupo }}</strong>.</p>
        <p>La fecha de inicio es: {{ $inscripcion->curso->fecha_inicio->format('d/m/Y') }}.</p>
    @elseif($inscripcion->estado === 'rechazado')
        <p>Lamentamos informarte que tu inscripción ha sido rechazada por el siguiente motivo:</p>
        <p><em>{{ $inscripcion->motivo_rechazo }}</em></p>
        <p>Por favor, revisa tus documentos y vuelve a postular si es posible.</p>
    @endif

    <p>Atentamente,<br>Dirección Académica - UPEA</p>
</body>
</html>
