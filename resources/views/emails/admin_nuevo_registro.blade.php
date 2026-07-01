<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { background: #004a99; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 20px; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #004a99; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Aspirante Registrado</h1>
        </div>
        <h2>Hola, {{ $admin->name }}</h2>
        <p>Se ha registrado un nuevo aspirante en el <strong>Sistema de Inscripciones para Cursos Preuniversitarios</strong>.</p>
        <h3>Datos del Aspirante:</h3>
        <ul>
            <li><strong>Nombre:</strong> {{ $aspirante->nombre_completo }}</li>
            <li><strong>Cédula:</strong> {{ $aspirante->ci }}</li>
            <li><strong>Correo:</strong> {{ $aspirante->correo }}</li>
            <li><strong>Celular:</strong> {{ $aspirante->celular }}</li>
            <li><strong>Colegio:</strong> {{ $aspirante->colegio_procedencia }}</li>
            <li><strong>Año de Egreso:</strong> {{ $aspirante->anio_egreso }}</li>
        </ul>
        <a href="{{ route('admin.aspirantes.index') }}" class="btn">Ver Aspirantes</a>
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas.</p>
            <p>&copy; 2026 Universidad Pública de El Alto</p>
        </div>
    </div>
</body>
</html>
