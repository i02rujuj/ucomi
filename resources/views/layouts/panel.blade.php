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

    <!-- Panel -->
    <div>
        <div id="menu" class="select-none transform -translate-x-full md:translate-x-0 transition-all duration-200 ease-in-out opacity-0 md:opacity-100 invisible md:visible md:flex lg:flex h-screen fixed top-14 left-0 bg-gray-100 z-50">
            <div class="w-64 h-screen px-6 py-4 bg-white shadow-lg">
                <div class="flex flex-col">
                    <img src="{{ asset('img/' . (Auth::user()->image ? Auth::user()->image : 'default_image.png')) }}" alt="Imagen de perfil" class="w-12 h-12 self-start ml-3 mb-1 justify-self-center rounded-full object-cover">
                    <h1 class="text-lg font-bold px-3">Bienvenido</h1>
                    <h3 class="text-xs text-gray-500 px-3 truncate">{{ auth()->user()->email }}</h3>
                </div>
        
                <hr class="my-4 border-gray-300" />
        
                <ul class="text-sm mt-2 leading-8">
                    <li @class(['mb-1 flex', request()->routeIs('centros') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('centros') }}" class="text-gray-600 w-full flex justify-start items-center">
                            <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                supervisor_account
                            </span>
                            &nbsp;
                            Centros
                        </a>
                    </li>
        
                    <li @class(['mb-1 flex', request()->routeIs('perfil') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="" class="text-gray-600 w-full flex justify-start items-center">
                            <span class="material-icons-round text-slate-600 ml-4 mr-2">
                                manage_accounts
                            </span>
                            &nbsp;
                            Mi Perfil
                        </a>
                    </li>
        
                    <li @class(['mb-1 flex', request()->routeIs('logout') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-700 w-full flex justify-start items-center">
                            <span class="material-icons-round text-red-600 ml-4 mr-2">
                                logout
                            </span>
                            &nbsp;
                            Cerrar Sesión
                        </a>
                    </li>
        
                </ul>
            </div>
        </div>

        <div class="mx-auto p-6">
            <main>@yield('content')</main>
        </div>

    </div>

</body>

</html>
