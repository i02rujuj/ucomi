<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <!-- Calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Axios -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.js"
        integrity="sha512-RjvSEaeDqPCfUVQ9kna2/2OqHz/7F04IOl1/66LmQjB/lOeAzwq7LrbTzDbz5cJzlPNJ5qteNtHR56XaJSTNWw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Vite Config -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="font-poppins bg-gray-100 text-sm">
    <!-- Barra de navegación Tailwind -->
    <nav class="bg-slate-900 fixed top-0 w-full z-50 shadow-md">
        <div class="mx-auto max-w-full sm:px-4 lg:px-8">
            <div class="flex justify-between md:justify-center items-center h-14">
                <div class="flex transition-all duration-200 ease-in-out">
                    <!--<a href="{{ /*route('welcome')*/ }}" class="flex justify-center items-center">-->
                        <img class="w-16 h-16" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                        <div
                            class="-ml-2 text-white text-2xl font-medium font-dancingscript underline underline-offset-4 justify-center items-center">
                            Ucomi
                        </div>
                    </a>
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
    @include('../components.shared.floating')
</body>

</html>
