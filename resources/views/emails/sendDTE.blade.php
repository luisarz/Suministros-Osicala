<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo de Laravel</title>
    <style>
        /* Aquí puedes agregar tu estilo CSS para el correo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: #7f8c8d;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
{{--    <h1>¡Hola, {{ $datos['nombre'] }}!</h1>--}}
    <p>Te enviamos este correo para informarte que tu solicitud ha sido procesada correctamente.</p>
    <p><strong>Detalles:</strong></p>
    <ul>
{{--        <li>Fecha: {{ $datos['fecha'] }}</li>--}}
{{--        <li>Estado: {{ $datos['estado'] }}</li>--}}
    </ul>
{{--    <a href="{{ $datos['url'] }}" class="btn">Ver más detalles</a>--}}
</div>
</body>
</html>
