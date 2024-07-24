@extends ('layouts.panel')
@section ('title')
Centros
@endsection

@section ('content')
    <div class="lg:ml-64 mt-14">
        <div class="mx-auto p-2">

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

            <div class="flex justify-between">
                
                @include('components.filtros.centroFiltro')

                @if($permitirAcciones = Auth::user()->esResponsable('admin'))
                    <div>
                        <div id="btn-add-centro" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                            <span class="material-icons-round scale-75">
                                add_circle
                            </span>
                            Añadir centro
                        </div>
                    </div>
                @endif
            </div>

            <div id="modal_add" name="modal_add" class="hidden w-full max-w-lg mt-4">

                <x-inputModal label="Nombre*" type="text" id="nombre" entidad="centro"></x-inputModal>
                <x-inputModal label="Dirección*" type="text" id="direccion" entidad="centro"></x-inputModal>
                
                <x-inputSelectModal label="Tipo*" id="idTipo" entidad="centro">
                    <option value='' selected disabled>Selecciona un tipo</option>
                    @foreach ($tiposCentro as $tipo)
                        <option value='{{$tipo->id}}'>{{$tipo->nombre}}</option>
                    @endforeach
                </x-inputSelectModal>     

                <div class="flex gap-x-4 justify-center items-center flex-wrap">
                    <div class="">
                        <img id="img_logo" name="img_logo" src="{{asset('img/default_image.png')}}" alt="Imagen de centro" class="w-16 h-16 mb-1 rounded-full object-cover">  
                    </div>
                    <div class="">
                        <input id="logo" name="logo" type="file" class="centro text-sm text-gray-600 border bg-blue-50 rounded-md mt-2 py-1 outline-none" />
                    </div>
                </div>      
            </div>

            <hr class="my-2 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">

                @if($centros && $centros[0])
                    @foreach ($centros as $centro)
                        <div id="btn-editar-centro" data-centro-id="{{ $centro['id'] }}" class="card bg-white p-4 rounded-lg shadow-md cursor-pointer">
                            
                            <div class="flex justify-start text-center items-center gap-2 mx-2">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-12 h-12 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>
                                <div class="flex justify-center text-center w-full">
                                    <h2 class="font-bold">{{$centro->tipo->nombre}} {{ $centro['nombre'] }}</h2>
                                </div>
                            </div>

                            <hr class="my-2">

                            <div class="flex items-center left-part w-full pl-3 z-10 gap-3 mt-2">

                                <div class="w-full ">  
                                    <div class="flex text-xs text-slate-400 font-medium items-center gap-1">
                                        <div class="flex items-center">
                                            <span class="material-icons-round scale-75">
                                                place
                                            </span>
                                            <div class="direccion">
                                                {{ $centro->direccion }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-2 gap-10" >
                                        <div class="flex flex-wrap gap-2">
                                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $centro->tipo->nombre }}</span>

                                            @if ($centro['deleted_at']!=null)    
                                                <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-end items-center gap-2" >
                                            <a id="btn-ver-miembros" data-centro-id="{{ $centro['id'] }}" class="group max-w-max absolute flex flex-col justify-end items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="{{route('miembrosGobierno')}}?filtroCentro={{ $centro['id'] }}&filtroRepresentacion=&filtroVigente=1&filtroEstado=1&action=filtrar">
                                                <span class="material-icons-round cursor-pointer">
                                                    groups
                                                </span>
                                                <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                                    <div class="flex max-w-xs flex-col items-center">
                                                        <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                            <span>Ver Miembros</span>
                                                        </div>
                                                        <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    No se han encontrado centros
                @endif

            </div>

            <div class="mt-5">{{$centros->appends([
                'filtroNombre' => $filtroNombre,
                'filtroTipo' => $filtroTipo,
                'filtroEstado' => $filtroEstado,
                'action' => $action,
                ])->links()}}
            </div>

        </div>
    </div>
    @endsection

<script>
    const default_image = "{{asset('img/default_image.png')}}"
    const permitirAcciones = "{{$permitirAcciones}}"
    const centros = @json($centros)
</script>

@vite(['resources/js/centros/centros.js'])