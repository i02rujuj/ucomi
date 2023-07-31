<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Ucomi') }}</title>
    <link rel="icon" type="image/ico" href="{{ asset('img/favicon.ico') }}"/>

    <meta name="robots" content="noindex, follow">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>  

<body>
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>4<span>0</span>4</h1>
            </div>
            <p>La página a la que estás intentando acceder no está disponible.
            </p>
            <a href="/">Página de inicio</a>
        </div>
    </div>
</body>

</html>
