<!DOCTYPE html>
<html>
<body>
    <h2>Hola, {{ $pago->inscripcion->aspirante->nombre_completo }}</h2>
    <p>Tu pago con comprobante Nº <strong>{{ $pago->numero_comprobante }}</strong> ha sido <strong>VERIFICADO Y APROBADO</strong>.</p>
    <p>Tu inscripción pasará ahora a la etapa final de revisión.</p>
    <p>Atentamente,<br>Dirección Académica - UPEA</p>
</body>
</html>
