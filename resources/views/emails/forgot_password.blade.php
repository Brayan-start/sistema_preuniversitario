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
            <h1>Recuperación de Contraseña</h1>
        </div>
        <h2>Hola, {{ $user->name }}</h2>
        <p>Has solicitado restablecer tu contraseña en el <strong>Sistema de Inscripciones para Cursos Preuniversitarios</strong>.</p>
        <p>Para crear una nueva contraseña, haz clic en el siguiente enlace:</p>
        <a href="{{ route('password.reset', $token) }}" class="btn">Restablecer Contraseña</a>
        <p style="margin-top: 20px;">Si no solicitaste este cambio, puedes ignorar este correo.</p>
        <p>Este enlace expirará en 60 minutos.</p>
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas.</p>
            <p>&copy; 2026 Universidad Pública de El Alto</p>
        </div>
    </div>
</body>
</html>
