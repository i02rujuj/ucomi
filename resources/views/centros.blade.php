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

                <div>
                    <div id="btn-add-centro" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                        <span class="material-icons-round scale-75">
                            add_circle
                        </span>
                        Añadir centro
                    </div>
                </div>
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
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

                                    @if ($centro['estado']==0)    
                                        <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                    @endif
                                </div>
                            </div>
                        </div>         
                    </div>
                @endforeach
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
</script>
@vite(['resources/js/centros/centros.js'])