<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #dc3545; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .observation { background: #f8d7da; border-left: 5px solid #dc3545; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pago Rechazado</h1>
        </div>
        <h2>Hola, {{ $pago->inscripcion->aspirante?->nombre_completo ?? 'Aspirante' }}</h2>
        <p>Lamentamos informarte que tu pago con comprobante Nº <strong>{{ $pago->numero_comprobante }}</strong> ha sido <strong>rechazado</strong>.</p>
        <div class="observation">
            <strong>Motivo del rechazo:</strong><br>
            <em>{{ $pago->motivo_rechazo }}</em>
        </div>
        <p><strong>Instrucciones:</strong> Por favor, ingresa a tu panel de aspirante y registra un nuevo comprobante bancario asegurándote de que los datos sean legibles y coincidan con el depósito realizado.</p>
        <div class="footer">
            <p>Atentamente,<br>Dirección Académica - UPEA</p>
        </div>
    </div>
</body>
</html>
