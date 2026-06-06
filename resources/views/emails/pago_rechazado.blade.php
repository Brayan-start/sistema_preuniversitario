<!DOCTYPE html>
<html>
<body>
    <h2>Hola, {{ $pago->inscripcion->aspirante->nombre_completo }}</h2>
    <p>Lamentamos informarte que tu pago con comprobante Nº <strong>{{ $pago->numero_comprobante }}</strong> ha sido <strong>RECHAZADO</strong>.</p>
    <p>Motivo: <em>{{ $pago->motivo_rechazo }}</em></p>
    <p>Por favor, sube una imagen clara del comprobante bancario en tu panel de aspirante.</p>
    <p>Atentamente,<br>Dirección Académica - UPEA</p>
</body>
</html>
