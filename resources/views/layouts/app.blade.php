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
                        @endif
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
