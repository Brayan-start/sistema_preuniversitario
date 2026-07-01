<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #28a745; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comprobante Recibido</h1>
        </div>
        <h2>Hola, {{ $pago->inscripcion->aspirante?->nombre_completo ?? 'Aspirante' }}</h2>
        <p>Hemos recibido correctamente tu comprobante de pago con el número: <strong>{{ $pago->numero_comprobante }}</strong>.</p>
        <p>Información del pago:</p>
        <ul>
            <li><strong>Monto:</strong> {{ $pago->monto }} Bs.</li>
            <li><strong>Fecha de pago:</strong> {{ $pago->fecha_pago->format('d/m/Y') }}</li>
        </ul>
        <p>Actualmente, tu pago se encuentra <strong>pendiente de revisión administrativa</strong>. Te notificaremos una vez sea verificado.</p>
        <div class="footer">
            <p>Atentamente,<br>Dirección Académica - UPEA</p>
        </div>
    </div>
</body>
</html>
