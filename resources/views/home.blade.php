@extends ('layouts.panel')
@section ('title')
Panel
@endsection

@section ('content')
<div class="lg:ml-64 mt-14">

    <div>
        @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
        @endif
            
        @if (session()->has('errors'))
            <div class="errorMessage my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("errors") }}
            </div>
        @endif
    </div>

    @if ($miembrosGobierno->count() || $miembrosJunta->count() ||$miembrosComision->count())
        <div class="bg-white p-8 mb-6 rounded-lg shadow-md mx-auto">

            <div class="text-lg">
                Tus representaciones vigentes en la Universidad de Córdoba
            </div>

            <div x-data="{ 
                openTab: 
                @if ($miembrosGobierno->count())
                    0
                @elseif ($miembrosJunta->count())
                    1
                @else
                    2
                @endif
                ,
                activeClasses: 'border-l border-t border-r rounded-t text-grey-700 font-semibold',
                inactiveClasses: 'text-grey-500 hover:border-l hover:border-t hover:border-r hover:rounded-t'
            }" class="px-2 py-6">

                <ul class="flex justify-start border-b mb-4">
                    @if($miembrosGobierno->count())
                        <li @click="openTab = 0" :class="{ '-mb-px': openTab === 0 }" class="-mb-px mr-1" @click.prevent="tab = 0">
                            <a href="#" :class="openTab === 0 ? activeClasses : inactiveClasses"
                                class="flex gap-2 bg-white py-2 px-4">
                                <span class="material-icons-round">
                                    account_balance
                                </span>
                                <span class="max-sm:hidden">Representante de Gobierno</span>
                            </a>
                        </li>
                    @endif
                    @if($miembrosJunta->count())
                        <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1" @click.prevent="tab = 1">
                            <a href="#" :class="openTab === 1 ? activeClasses : inactiveClasses"
                                class="flex gap-2 bg-white py-2 px-4">
                                <span class="material-icons-round">
                                    workspaces
                                </span>
                                <span class="max-sm:hidden">Representante de Junta</span>
                            </a>
                        </li>
                    @endif
                    @if($miembrosComision->count())
                        <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="-mb-px mr-1" @click.prevent="tab = 2">
                            <a href="#" :class="openTab === 2 ? activeClasses : inactiveClasses"
                                class="flex gap-2 bg-white py-2 px-4">
                                <span class="material-icons-round">
                                    send
                                </span>
                                <span class="max-sm:hidden">Representante de Comisión</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="w-full">

                    {{--GOBIERNO--}}
                    <div x-show="openTab === 0">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                                @if($miembrosGobierno->count())
                                    @foreach ($miembrosGobierno as $miembro)
                                        <div class="card bg-white p-2 rounded-lg shadow-md mt-2">
                                            
                                            <div class="flex justify-start text-center items-center gap-4">
                                                <div class="right-part w-full max-w-max">
                                                    <img src="{{ $miembro->usuario->image ? $miembro->usuario->image : asset('img/default_image_profile.jpg') }}" alt="Imagen de usuario" class="shadow-black shadow-sm w-16 h-16 ml-1 mb-1 justify-self-center rounded-md object-cover">  
                                                    <img src="{{ $miembro->centro->logo ? $miembro->centro->logo : asset('img/default_image.jpg') }}" alt="Imagen de centro" class="shadow-black shadow-sm -mt-9 w-8 h-8 ml-1 mb-1 justify-self-center rounded-md ">  
                                                </div>
                                                <h2 class="font-bold">{{ $miembro->representacion->nombre }}</h2>
                                            </div>

                                            <div class="flex gap-3 mt-2">
                                                <div class="left-part">
                    
                                                    <div class="flex text-xs text-slate-400 font-medium items-center gap-1">
                                                        <div class="flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                school
                                                            </span>
                                                            <h2 class="ml-1">Centro '{{ $miembro->centro->tipo->nombre }} {{ $miembro->centro->nombre }}'</h2>
                                                        </div>
                                                    </div> 
                    
                                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                                        <div class="truncate flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                event
                                                            </span>
                                                            <div class="fechaTomaPosesion truncate ml-1">
                    
                                                                {{ $miembro->fecha_toma_posesion_format }} | 
                                                                
                                                                @empty ($miembro->fechaCese)
                                                                    Actualidad
                                                                @else
                                                                    {{ $miembro->fecha_cese_format }}
                                                                @endempty
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                    
                                            <div class="flex flex-wrap justify-end items-center gap-2 mt-2">
                                                <span class="flex items-center text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                                    @if ($miembro->responsable==1)
                                                    <span class="text-sm material-icons-round text-yellow-700">
                                                        workspace_premium
                                                    </span>
                                                    Responsable de Centro
                                                    @else
                                                        Miembro de Gobierno
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--JUNTA--}}
                    <div x-show="openTab === 1">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                                @if($miembrosJunta->count())
                                    @foreach ($miembrosJunta as $miembro)
                                        <div class="card bg-white p-2 rounded-lg shadow-md mt-2">

                                            <div class="flex justify-start text-center items-center gap-4">
                                                <div class="right-part w-full max-w-max">
                                                    <img src="{{ $miembro->usuario->image ? $miembro->usuario->image : asset('img/default_image_profile.jpg') }}" alt="Imagen de usuario" class="shadow-black shadow-sm w-16 h-16 ml-1 mb-1 justify-self-center rounded-md object-cover">  
                                                    <img src="{{ $miembro->junta->centro->logo ? $miembro->junta->centro->logo : asset('img/default_image.jpg') }}" alt="Imagen de centro" class="shadow-black shadow-sm -mt-9 w-8 h-8 ml-1 mb-1 justify-self-center rounded-md ">  
                                                </div>
                                                <h2 class="font-bold">{{ $miembro->representacion->nombre }}</h2>
                                            </div>                       

                                            <div class="flex gap-3 mt-2">
                                                <div class="left-part ">

                                                    <div class="flex text-xs text-slate-400 font-medium items-center gap-1">
                                                        <div class="flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                workspaces
                                                            </span>
                                                            <h2 class="ml-1 ">Junta de {{ $miembro->junta->centro->tipo->nombre }} {{ $miembro->junta->fecha_constitucion_format }}</h2>
                                                        </div>
                                                    </div>
                
                                                    <div class="flex text-xs text-slate-400 font-medium items-center gap-1">
                                                        <div class="flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                school
                                                            </span>
                                                            <h2 class="ml-1">{{ $miembro->junta->centro->nombre }}</h2>
                                                        </div>
                                                    </div> 
                
                                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                                        <div class="truncate flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                event
                                                            </span>
                                                            <div class="fechaTomaPosesion truncate">
                
                                                                {{ $miembro->fecha_toma_posesion_format }} | 
                                                                
                                                                @empty ($miembro->fechaCese)
                                                                    Actualidad
                                                                @else
                                                                    {{ $miembro->fecha_cese_format }}
                                                                @endempty
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="flex flex-wrap justify-end items-center gap-2 mt-2">
                                                <span class="flex items-center text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                                    @if ($miembro->responsable==1)
                                                    <span class="text-sm material-icons-round text-yellow-700">
                                                        workspace_premium
                                                    </span>
                                                    Responsable de Junta
                                                    @else
                                                        Miembro de Junta
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--COMISIÓN--}}
                    <div x-show="openTab === 2">
                        <div class="">
                            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                                @if($miembrosComision->count())
                                    @foreach ($miembrosComision as $miembro)
                                        <div class="card bg-white p-2 rounded-lg shadow-md mt-2">

                                            <div class="flex justify-start text-center items-center gap-4">
                                                <div class="right-part w-full max-w-max">
                                                    <img src="{{ $miembro->usuario->image ? $miembro->usuario->image : asset('img/default_image_profile.jpg') }}" alt="Imagen de usuario" class="shadow-black shadow-sm w-16 h-16 ml-1 mb-1 justify-self-center rounded-md object-cover">  
                                                    <img src="{{ $miembro->comision->junta->centro->logo ? $miembro->comision->junta->centro->logo : asset('img/default_image.jpg') }}" alt="Imagen de centro" class="shadow-black shadow-sm -mt-9 w-8 h-8 ml-1 mb-1 justify-self-center rounded-md ">  
                                                </div>
                                                <h2 class="font-bold">{{ $miembro->representacion->nombre }}</h2>
                                            </div>

                                            <div class="flex gap-3 mt-2">                                            
                                                <div class="left-part w-full">
               
                                                    <div class="flex items-center gap-1 text-xs text-slate-400 font-medium">
                                                        <div class="flex w-full items-center">
                                                        <span class="material-icons-round scale-75">
                                                            send
                                                        </span>
                                                        <h2 class="ml-1">Comisión '{{ $miembro->comision->nombre }}'</h2>
                                                        </div>
                                                    </div>
                
                                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                                        <div class="truncate flex items-center">
                                                            <span class="material-icons-round scale-75">
                                                                event
                                                            </span>
                                                            <div class="fechaTomaPosesion truncate ml-1">
                
                                                                {{ $miembro->fecha_toma_posesion_format }} | 
                                                                
                                                                @empty ($miembro->fechaCese)
                                                                    Actualidad
                                                                @else
                                                                    {{ $miembro->fecha_cese_format }}
                                                                @endempty
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="flex flex-wrap justify-end items-center gap-2 mt-2">
                                                <span class="flex items-center text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                                    @if ($miembro->responsable==1)
                                                        <span class="text-sm material-icons-round text-yellow-700">
                                                            workspace_premium
                                                        </span>
                                                        Responsable de Comisión
                                                        @else
                                                        Miembro de Comisión
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
        </div>
    @else
    <div class="flex flex-wrap justify-center items-center mx-auto py-8 gap-10">
        <p class="text-lg text-center my-4">Actualmente no tienes ninguna representación vigente en la Universidad de Córdoba</p>
        <img id="carousel-image" src="{{ asset('img/inicio1.png') }}" alt="Beneficio 1" class="rounded-lg md:w-2/3 h-auto object-cover" />
    </div>
    @endif
</div>

@endsection
