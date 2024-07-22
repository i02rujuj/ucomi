<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación convocado</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px; /* Aumentado el padding para más espacio */
            background-color: #ffffff;
            border: 1px solid #ddd;
            text-align: left; /* Alinea el texto a la izquierda */
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #26c6da; /* Color del botón */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            font-size: 12px;
            color: #777777;
            margin-top: 20px;
        }
        .header {
            font-weight: bold; /* Hace el título en negritas */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{asset('img/logo.jpg')}}" alt="Ucomi" style="height: 100px; margin: 0 auto 20px; display: block;">
        <h1 class="header">¡Has sido convocado!</h1>
        <p>Hola, {{$mailData['usuario']}}:</p>
        <p>Has sido convocado a la convocatoria de {{$mailData['tipoConvocatoria']}} del centro de la Universidad de Córdoba '{{$mailData['organo']}}' en la siguiente fecha:</p>
        <h5>Lugar: {{$mailData['lugar']}}</h5>
        <h5>Fecha: {{$mailData['fecha']}}</h5>
        <h5>Hora: {{$mailData['hora']}}</h5>
        <p>Necesitamos confirmar su asistencia.</p>
        <a href="{{$mailData['url']}}" class="button">Confirmar asistencia</a>
        <p class="footer">Si tienes alguna pregunta o necesitas asistencia, no dudes en ponerte en contacto con nuestro equipo de soporte.</p>
        <p class="footer">Atentamente,</p>
        <p class="footer">Ucomi</p>
    </div>
</body>
</html>