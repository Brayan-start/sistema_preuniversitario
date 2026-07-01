<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #28a745; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pago Verificado y Aprobado</h1>
        </div>
        <h2>Hola, {{ $pago->inscripcion->aspirante?->nombre_completo ?? 'Aspirante' }}</h2>
        <p>Te informamos que tu pago ha sido <strong>verificado y aprobado</strong> exitosamente.</p>
        <div class="details">
            <ul>
                <li><strong>Comprobante Nº:</strong> {{ $pago->numero_comprobante }}</li>
                <li><strong>Monto:</strong> Bs. {{ number_format($pago->monto, 2) }}</li>
                <li><strong>Curso:</strong> {{ $pago->inscripcion->curso->nombre_curso }}</li>
            </ul>
        </div>
        <p>Tu inscripción está en la etapa final de revisión. Recibirás una notificación cuando sea aprobada completamente.</p>
        <div class="footer">
            <p>Atentamente,<br>Dirección Académica - UPEA</p>
        </div>
    </div>
</body>
</html>
