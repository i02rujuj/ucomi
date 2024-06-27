@extends ('layouts.panel')
@section ('title')
Centros
@endsection

@section ('content')
    <div class="md:ml-64 lg:ml-64 mt-14">
        <div class="mx-auto p-6">

            @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
            @endif
            
            @if (session()->has('error'))
            <div class="my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("error") }}
            </div>
            @endif

            <form method="POST" action="{{ route('centros.store') }}" enctype="multipart/form-data" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <h2 class="text-gray-600 font-bold mb-2">Añadir nuevo centro</h2>
                @csrf
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-2">

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="nombre" class="block text-sm text-gray-600 mb-1">
                                Nombre:
                            </label>
                            <input id="nombre" name="nombre" type="text" value="{{old("nombre")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('nombre')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div>
                            <label for="direccion" class="block text-sm text-gray-600 mb-1">
                                Dirección:
                            </label>
                            <input id="direccion" name="direccion" type="text" value="{{old("direccion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('direccion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idTipo" class="block text-sm text-gray-600 mb-1">
                                Tipo:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idTipo" name="idTipo" value="{{old("idTipo")}}">
                                <option value="">-----</option>
                                @foreach ($tiposCentro as $tipo)
                                    <option value="{{ $tipo['id'] }}" {{ (old("idTipo")== $tipo['id'] || app('request')->input('idTipo') == $tipo['id'] ? "selected":"") }}>{{ $tipo['nombre'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idTipo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div> 
                    </div> 

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="logo" class="block text-sm text-gray-600 mb-1">
                                Logotipo:
                            </label>
                            <input id="logo" name="logo" type="file" value="{{old("logo")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off"/>
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Añadir Centro
                </button>
            </form>

            <hr className="my-6 border-t border-gray-300" />

<!----------------------------- START FILTROS ---------------------------------->

            <button class="accordion w-full text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">Filtros</button>
            <div class="panel">

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-2">

                    <div class="left-side w-full"> 

                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">
                            <span class="block text-sm text-gray-600 mb-1">
                                Texto: 
                            </span>
                            <input type="text" id="search-input" class="text-sm text-gray-600 border py-1 w-full outline-none bg-white px-2 rounded form-input" placeholder="Buscar..." value="{{ request('centro') }}">
                        </div>
                    </div>          
                </div>
            </div>

<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADOS ---------------------------------->

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @foreach ($centros as $centro)
                    <div id="btn-editar-centro" data-centro-id="{{ $centro['id'] }}" class="card bg-white p-4 rounded-lg shadow-md cursor-pointer">
                        <div class="flex items-start">
                            <div class="right-part w-full max-w-max">
                                <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                            </div>

                            <div class="left-part truncate w-full max-w-max pl-3">
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
                                    @if ($centro['estado']==1)
                                        <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                    @else
                                        <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                    @endif
                                </div>
                            </div>
                        </div>         
                    </div>
                @endforeach
            </div>

<!----------------------------- END LISTADOS ---------------------------------->

<!----------------------------- START PAGINACIÓN ---------------------------------->

<div class="mt-5">{{$centros->links()}}</div>

<!----------------------------- END LISTADOS ---------------------------------->


        </div>
    </div>
    @endsection

<script>
    const default_image = "{{asset('img/default_image.png')}}"
</script>
@vite(['resources/js/centros/centros.js'])
@vite(['resources/js/filtros.js'])