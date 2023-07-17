<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <!-- Vite Config -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-sm">
    
    <nav class="bg-white fixed top-0 w-full z-50 shadow-md">
        <div class="mx-auto max-w-full sm:px-4 lg:px-8">
            <div class="flex justify-between md:justify-center items-center h-14">
                <div class="flex transition-all duration-200 ease-in-out">
                    <img class="h-12" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                </div>
                <button id="mostrar_menu" class="md:hidden mr-5 text-white">
                    <span class="material-icons-round scale-125">
                        menu
                    </span>
                </button>
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>
</body>

</html>
