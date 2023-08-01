<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" type="image/ico" href="{{ asset('img/favicon.ico') }}"/>

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <script>
        var asset_global='{{asset("img")}}'
    </script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>

<!-- START Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0T5L2JGC2L"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-0T5L2JGC2L');
</script>
<!-- END Analytics -->

<body>

    <nav class="bg-white fixed top-0 w-full z-50">
        <div class="mx-auto max-w-full sm:px-4 lg:px-8">
            <div class="flex items-center justify-between h-20 ml-2">
                    <div class="flex transition-all duration-200 ease-in-out">
                        <a href="{{ route('welcome') }}">
                            <img class="h-12" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                        </a>
                    </div> 
                               
                <div class="md:block">
                    <div class="ml-4 flex items-center md:ml-6"> 
                        @if (!Auth::check())
                            <a href="{{ route('login') }}" class="ml-3 px-3 py-1 text-sm font-medium rounded-md hover:text-white hover:bg-gray-700">Login</a>
                        @else
                            <a href="{{ route('home') }}" class="ml-3 px-3 py-1 text-sm font-medium rounded-md hover:text-white hover:bg-gray-700">{{Auth::user()->name}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('contentTop')

        <!-- Sección búsqueda del centro -->
        <section class="bg-ucomi bg-cover lg:h-screen md:h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full w-full flex items-center justify-center">
                <div class="text-center">
                    <div class="mt-20">
                        <div class="sentences_together flex flex-col md:flex-row">
                            <h2 class="text-lg font-bold text-white mb-4">
                                Obtén información pública sobre las comisiones de los diferentes centros de la Universidad de Córdoba
                            </h2>
                        </div>

                        <p class="text-white mb-4">
                            También encontrarás información relativa a la composición del equipo de gobierno, juntas y comisiones de cada centro
                        </p>
                        
                        <form action="{{ route('infoPublica') }}" method="GET">
                            <select class="max-sm:w-52 text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none required" id="idCentro" name="idCentro" required>
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro['id'] }}">{{ $centro['nombre'] }}</option>
                                @endforeach
                            </select>

                            <div class="buttons flex justify-center items-center gap-6 py-4">
                                <button type="submit" class="md:w-36 w-1/2 inline-block px-6 py-3 font-bold text-white bg-blue-600 rounded-md shadow-md">
                                    Buscar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @yield('contentBottom')
    </main>

    @include('components.footer')
    
</body>
</html>
