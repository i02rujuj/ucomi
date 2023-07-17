<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ucomi') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    <nav class="bg-white fixed top-0 w-full z-50">
        <div class="mx-auto max-w-full sm:px-4 lg:px-8">
            <div class="flex items-center justify-center md:justify-between h-20">
                <div class="flex justify-center items-center">
                    <div class="flex-shrink-0">
                        <a href="/" class="flex justify-center items-center">
                            <img class="h-14" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @include('components.footer')
    
</body>
</html>
