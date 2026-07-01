<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #ffc107; color: #333; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .observation { background: #fff3cd; border-left: 5px solid #ffc107; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Documentos Observados</h1>
        </div>
        <h2>Hola, {{ $documento->aspirante?->nombre_completo ?? 'Aspirante' }}</h2>
        <p>Te informamos que se han encontrado observaciones en el documento que subiste: <strong>{{ strtoupper($documento->tipo) }}</strong>.</p>
        
        <div class="observation">
            <strong>Detalle de la observación:</strong><br>
            {{ $observaciones }}
        </div>

        <p><strong>Instrucciones:</strong> Por favor, ingresa a tu panel de aspirante, elimina el archivo actual y sube uno nuevo que cumpla con los requisitos solicitados (archivo claro, legible y en el formato correcto).</p>
        
        <div class="footer">
            <p>Atentamente,<br>Dirección Académica - UPEA</p>
        </div>
    </div>
</body>
</html>
