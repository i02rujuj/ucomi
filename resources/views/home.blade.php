@extends ('layouts.panel')
@section ('title')
Panel
@endsection

@section ('content')
<div>
    <div id="menu" class="select-none transform -translate-x-full md:translate-x-0 transition-all duration-200 ease-in-out opacity-0 md:opacity-100 invisible md:visible md:flex lg:flex h-screen fixed top-14 left-0 bg-gray-100 z-50">
        <div class="w-64 h-screen px-6 py-4 bg-white shadow-lg">
            <div class="flex flex-col">
                <img src="{{ asset('img/' . (Auth::user()->image ? Auth::user()->image : 'default_image.png')) }}" alt="Imagen de perfil" class="w-12 h-12 self-start ml-3 mb-1 justify-self-center rounded-full object-cover">
                <h1 class="text-lg font-bold px-3">Bienvenido,</h1>
                <h3 class="text-xs text-gray-500 px-3 truncate">{{ auth()->user()->email }}</h3>
            </div>
    
            <hr class="my-4 border-gray-300" />
    
            <ul class="text-sm mt-2 leading-8">
                <li @class(['mb-1 flex', request()->routeIs('centros') ? 'px-3 font-medium hover:font-semibold bg-blue-100 w-full rounded-md box-border' : 'hover:px-3 hover:bg-blue-50 hover:rounded-md ease-in-out hover:transition-all duration-200'])>
                    <a href="" class="text-gray-600 w-full flex justify-start items-center">
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
</div>

<!-- Contenido Principal -->
@endsection
