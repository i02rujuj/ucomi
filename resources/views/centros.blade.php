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

            <div id="modal_add" name="modal_add" class="hidden">
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-1 justify-center items-center">
                    <label for="nombre" class="block text-sm text-gray-600 w-32 text-right">Nombre *</label>
                    <input type="text" id="nombre" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none required">
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-3 justify-center items-center">
                    <label for="direccion" class="block text-sm text-gray-600 w-32 text-right">Direccion *</label>
                    <input type="text" id="direccion" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none required">
                </div>
        
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                    <label for="idTipo" class="block text-sm text-gray-600 mb-1 w-32 pr-7 text-right">Tipo *</label>
                    <select id="idTipo" class="swal2-input centro tipo text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none required">
                        <option value="" selected disabled>Selecciona un tipo</option>
                        @foreach ($tiposCentro as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                    <label for="img_logo" class="block text-sm text-gray-600 w-32 text-right">
                        <img id="img_logo" name="img_logo" src="{{asset('img/default_image.png')}}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                    </label>
                    <input id="logo" name="logo" type="file" class="centro w-60 text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" autocomplete="off" />
                </div>      
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">

                @if($centros && $centros[0])
                    @foreach ($centros as $centro)
                        <div id="btn-editar-centro" data-centro-id="{{ $centro['id'] }}" class="card bg-white p-4 rounded-lg shadow-md cursor-pointer">
                            <div class="flex items-center">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="left-part truncate w-full max-w-max pl-3 z-10">
                                    <div class="flex items-start">
                                        <span class="material-icons-round scale-75">
                                            school
                                        </span>
                                        &nbsp;
                                        <h2 class="text-base font-bold truncate">{{ $centro['nombre'] }}</h2>
                                    </div>

                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                place
                                            </span>
                                            <div class="direccion truncate">
                                                {{ $centro->direccion }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-2" >
                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $centro->tipo->nombre }}</span>

                                        @if ($centro['deleted_at']!=null)    
                                            <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                        @endif
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