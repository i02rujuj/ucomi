<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <link rel="icon" type="image/ico" href="{{ asset('img/favicon.ico') }}"/>

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />

    <!-- Vite Config -->
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

<body class="bg-gray-100 text-sm">
    
    <nav class="bg-white fixed top-0 w-full z-50 shadow-md">
        <div class="mx-auto max-w-full sm:px-4 lg:px-8">
            <div class="flex justify-between md:justify-center items-center h-14">
                <div class="flex transition-all duration-200 ease-in-out">
                    <a href="{{ route('welcome') }}">
                        <img class="h-12" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                    </a>
                </div> 
                <button id="mostrar_menu" class="md:hidden mr-5 text-black">
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
                    <img src="{{ Auth::user()->image ? Cloudinary::getUrl(Auth::user()->image) : asset('uploads/img/default_image.png') }}" alt="Imagen de perfil" class="w-12 h-12 self-start ml-3 mb-1 justify-self-center rounded-full object-cover">
                    <h1 class="text-lg font-bold px-3">Bienvenido</h1>
                    <h3 class="text-xs text-gray-500 px-3 truncate">{{ auth()->user()->email }}</h3>
                    <h3 class="text-xs text-gray-500 px-3 truncate">{{ auth()->user()->getRoleName() }}</h3>
                    <h3 class="text-xs text-gray-500 px-3">{{ auth()->user()->getRoleNameType() }}</h3>
                </div>
        
                <hr class="my-4 border-gray-300" />
        
                <ul class="text-sm mt-2 leading-8">

                    @hasrole('admin')
                    <li @class(['mb-1 flex', request()->routeIs('centros') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('centros') }}" class="text-gray-600 w-full flex justify-start items-center">
                            <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                school
                            </span>
                            &nbsp;
                            Centros
                        </a>
                    </li>
                    @endhasrole

                    @hasrole(['admin', 'responsable_centro'])
                    <li @class(['mb-1 flex', request()->routeIs('juntas') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('juntas') }}" class="text-gray-600 w-full flex justify-start items-center">
                            <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                account_balance
                            </span>
                            &nbsp;
                            Juntas
                        </a>
                    </li> 
                    @endhasrole

                    @hasrole(['admin', 'responsable_centro', 'responsable_junta'])
                    <li @class(['mb-1 flex', request()->routeIs('comisiones') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('comisiones') }}" class="text-gray-600 w-full flex justify-start items-center">
                            <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                send
                            </span>
                            &nbsp;
                            Comisiones
                        </a>
                    </li>
                    @endhasrole

                    @hasrole(['admin', 'responsable_centro', 'responsable_junta', 'responsable_comision'])
                    
                    <button class="accordion-submenu text-gray-600 w-full flex justify-start items-center hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200">
                        <span class="material-icons-round text-slate-600 ml-4 mr-2">
                            event
                        </span>
                        
                        &nbsp;
                            Convocatorias

                        <span class="material-icons-round text-slate-600 ml-2">
                            expand_more
                        </span>
                    </button>
                    
                    <div @class(['submenu', request()->routeIs('convocatoriasJunta') || request()->routeIs('convocatoriasComision') ? 'submenu-visible' : ''])>
                    
                        @hasrole(['admin', 'responsable_centro', 'responsable_junta'])

                        <li @class(['mb-1 flex', request()->routeIs('convocatoriasJunta') ? 'px-9 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'px-6 hover:px-9 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                            <a href="{{ route('convocatoriasJunta') }}" class="text-gray-600 w-full flex justify-start items-center">
                                <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                    event
                                </span>
                                &nbsp;
                                Junta
                            </a>
                        </li>

                        @endhasrole

                        @hasrole(['admin', 'responsable_centro', 'responsable_junta', 'responsable_comision'])

                        <li @class(['mb-1 flex', request()->routeIs('convocatoriasComision') ? 'px-9 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'px-6 hover:px-9 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                            <a href="{{ route('convocatoriasComision') }}" class="text-gray-600 w-full flex justify-start items-center">
                                <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                    event
                                </span>
                                &nbsp;
                                Comisión
                            </a>
                        </li>
                        @endhasrole
                        
                    </div>

                    @endhasrole

                    <button class="accordion-submenu text-gray-600 w-full flex justify-start items-center hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200">
                        <span class="material-icons-round text-slate-600 ml-4 mr-2">
                            manage_accounts
                        </span>
                        
                        &nbsp;
                            Miembros

                        <span class="material-icons-round text-slate-600 ml-2">
                            expand_more
                        </span>
                    </button>

                    <div @class(['submenu', request()->routeIs('miembrosGobierno') || request()->routeIs('miembrosJunta') || request()->routeIs('miembrosComision') ? 'submenu-visible' : ''])>
                        
                        @hasrole(['admin','responsable_centro'])
                        <li @class(['mb-1 flex', request()->routeIs('miembrosGobierno') ? 'px-9 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'px-6 hover:px-9 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                            <a href="{{ route('miembrosGobierno') }}" class="text-gray-600 w-full flex justify-start items-center">
                                <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                    manage_accounts
                                </span>
                                &nbsp;
                                Gobierno
                            </a>
                        </li>
                        @endhasrole

                        @hasrole(['admin','responsable_centro','responsable_junta'])
                        <li @class(['mb-1 flex', request()->routeIs('miembrosJunta') ? 'px-9 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'px-6 hover:px-9 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                            <a href="{{ route('miembrosJunta') }}" class="text-gray-600 w-full flex justify-start items-center">
                                <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                    manage_accounts
                                </span>
                                &nbsp;
                                Junta
                            </a>
                        </li>
                        @endhasrole

                        @hasrole(['admin', 'responsable_centro', 'responsable_junta','responsable_comision'])
                        <li @class(['mb-1 flex', request()->routeIs('miembrosComision') ? 'px-9 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'px-6 hover:px-9 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                            <a href="{{ route('miembrosComision') }}" class="text-gray-600 w-full flex justify-start items-center">
                                <span class="material-icons-round text-slate-600 ml-4 mr-1">
                                    manage_accounts
                                </span>
                                &nbsp;
                                Comisión
                            </a>
                        </li>
                        @endhasrole
                        
                    </div>

                    <hr class="my-4 border-gray-300" />

                    <li @class(['mb-1 flex', request()->routeIs('perfil') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                        <a href="{{ route('perfil') }}" class="w-full flex justify-start items-center">
                            <span class="material-icons-round ml-4 mr-2">
                                account_box
                            </span>
                            &nbsp;
                            Mi perfil
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

    @vite(['resources/js/home.js'])
</body>

</html>
