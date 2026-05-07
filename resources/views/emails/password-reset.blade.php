er<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(90deg, #b23a3a, #ff6b6b);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
            color: #333;
            line-height: 1.6;
        }
        .content h2 {
            color: #b23a3a;
            margin-top: 0;
        }
        .content p {
            margin: 10px 0;
        }
        .code-box {
            background-color: #f8f9fa;
            border: 2px dashed #b23a3a;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .code-number {
            font-size: 36px;
            font-weight: bold;
            color: #b23a3a;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Código de Recuperación</h1>
        </div>

        <div class="content">
            <h2>¡Hola, {{ $user->name }}!</h2>

            <p>Recibimos una solicitud para recuperar tu contraseña. Si no fuiste tú, puedes ignorar este email.</p>

            <p>Usa el siguiente código para resetear tu contraseña:</p>

            <div class="code-box">
                <div class="code-number">{{ $code }}</div>
            </div>

            <p>Ingresa este código en el formulario de recuperación de contraseña.</p>

            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('password.reset', ['token' => $code]) }}" style="background: #b23a3a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Resetear Contraseña</a>
            </div>

            <div class="warning">
                ⏰ <strong>Este código expirará en 60 minutos.</strong> Si necesitas recuperar tu contraseña después de ese tiempo, solicita un nuevo código.
            </div>

            <p><strong>Instrucciones:</strong></p>
            <ol>
                <li>Copia el código de arriba o haz clic en el botón "Resetear Contraseña"</li>
                <li>Ingresa el código y tu nueva contraseña</li>
                <li>¡Listo! Inicia sesión con tu nueva contraseña</li>
            </ol>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                Si tienes problemas, contacta con nuestro equipo de soporte.
            </p>
        </div>

        <div class="footer">
            <p>
                © {{ date('Y') }} Hotel Muñoz. Todos los derechos reservados.<br>
                Este es un email automatizado, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>