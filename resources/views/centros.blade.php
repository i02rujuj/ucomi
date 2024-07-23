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

                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro'))
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
                <div class="sm:flex sm:items-center mb-6">
                    <div class="sm:w-1/4">
                        <label for="nombre" class="block text-sm text-gray-600 sm:text-right mb-1 sm:mb-0 pr-4">Nombre *</label>
                    </div>
                    <div class="sm:w-3/4">
                        <input type="text" id="nombre" class="centro text-sm text-gray-600 border bg-blue-50 rounded-md appearance-none border-gray-200 w-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                    </div>
                </div>

                <div class="sm:flex sm:items-center mb-6">
                    <div class="sm:w-1/4">
                        <label for="direccion" class="block text-sm text-gray-600 sm:text-right mb-1 sm:mb-0 pr-4">Direccion *</label>
                    </div>
                    <div class="sm:w-3/4">
                        <input type="text" id="direccion" class="centro text-sm text-gray-600 border bg-blue-50 rounded-md appearance-none border-gray-200 w-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                    </div>
                </div>
        
                <div class="sm:flex sm:items-center mb-2">
                    <div class="sm:w-1/4">
                        <label for="idTipo" class="block text-sm text-gray-600 sm:text-right mb-1 sm:mb-0 pr-4">Tipo *</label>
                    </div>
                    <div class="sm:w-3/4">
                        <select id="idTipo" class="centro text-sm text-gray-600 border bg-blue-50 rounded-md appearance-none border-gray-200 w-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
                            <option value="" selected disabled>Selecciona un tipo</option>
                            @foreach ($tiposCentro as $tipo)
                                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-x-4 justify-center items-center flex-wrap">
                    <div class="">
                        <label for="img_logo" class="block text-sm text-gray-600">
                            <img id="img_logo" name="img_logo" src="{{asset('img/default_image.png')}}" alt="Imagen de centro" class="w-16 h-16 mt-2 mb-1 rounded-full object-cover">  
                        </label>
                    </div>
                    <div class="">
                        <input id="logo" name="logo" type="file" class="centro text-sm text-gray-600 border bg-blue-50 rounded-md mt-2 py-1 outline-none" />
                    </div>
                </div>      
            </div>

            <hr class="my-2 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">

                @if($centros && $centros[0])
                    @foreach ($centros as $centro)
                        <div id="btn-editar-centro" data-centro-id="{{ $centro['id'] }}" class="card bg-white p-4 rounded-lg shadow-md cursor-pointer">
                            
                            <div class="flex justify-center text-center items-center ">
                                <h2 class="md:truncate font-bold">{{ $centro['nombre'] }}</h2>
                            </div>

                            <hr class="my-2">

                            <div class="flex items-center left-part w-full pl-3 z-10 gap-3">
                                <div class="w-full max-w-max">
                                    <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-12 h-12 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="w-full">  
                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1 mt-3">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                place
                                            </span>
                                            <div class="direccion truncate">
                                                {{ $centro->direccion }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-2 gap-10" >
                                        <div>
                                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $centro->tipo->nombre }}</span>

                                            @if ($centro['deleted_at']!=null)    
                                                <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-end items-center gap-2" >
                                            <a id="btn-ver-miembros" data-centro-id="{{ $centro['id'] }}" class="group max-w-max absolute flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="{{route('miembrosGobierno')}}?filtroCentro={{ $centro['id'] }}&filtroRepresentacion=&filtroVigente=1&filtroEstado=1&action=filtrar">
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