<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
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
        }
        .content h1 {
            color: #b23a3a;
            margin-bottom: 20px;
        }
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content li {
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Reserva</h1>
        </div>
        <div class="content">
            <h1>¡Reserva Confirmada!</h1>

            <p>Hola <strong>{{ $guest->name }}</strong>,</p>

            <p>Tu reserva ha sido creada exitosamente. Aquí están los detalles:</p>

            <ul>
                <li><strong>Habitación:</strong> {{ $reservation->room->numero }} - {{ $reservation->room->tipo }}</li>
                <li><strong>Fecha de entrada:</strong> {{ $reservation->fecha_entrada->format('d/m/Y') }}</li>
                <li><strong>Fecha de salida:</strong> {{ $reservation->fecha_salida->format('d/m/Y') }}</li>
                <li><strong>Total:</strong> ${{ number_format($reservation->total, 2, ',', '.') }}</li>
                <li><strong>Estado:</strong> {{ ucfirst($reservation->status) }}</li>
            </ul>

            <p>Si tienes alguna pregunta, contáctanos.</p>

            <p>¡Gracias por elegirnos!</p>

            <p>Atentamente,<br>
            Equipo del Hotel</p>
        </div>
        <div class="footer">
            <p>Este es un email automático. Por favor, no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html></content>
<parameter name="filePath">c:\laragon\www\proyectointegrador\resources\views\emails\reservation-confirmation.blade.php