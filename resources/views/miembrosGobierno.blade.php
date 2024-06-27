@extends ('layouts.panel')
@section ('title')
Miembros de Gobierno
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

            <form method="POST" action="{{ route('miembrosGobierno.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <h2 class="text-gray-600 font-bold mb-2">Añadir nuevo miembro de Gobierno</h2>
                @csrf
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idUsuario" class="block text-sm text-gray-600 mb-1">
                                Usuario:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idUsuario" name="idUsuario">
                                <option value="">-----</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user['id'] }}" {{ (old("idUsuario")== $user['id'] || app('request')->input('idUsuario') == $user['id'] ? "selected":"") }}>{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idUsuario')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idRepresentacion" class="block text-sm text-gray-600 mb-1">
                                Representación:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idRepresentacion" name="idRepresentacion">
                                <option value="">-----</option>
                                @foreach ($representacionesGobierno as $rep)
                                    <option value="{{ $rep['id'] }}" {{ (old("idRepresentacion")== $rep['id'] || app('request')->input('idRepresentacion') == $rep['id'] ? "selected":"") }}>{{ $rep['nombre'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idRepresentacion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idCentro" class="block text-sm text-gray-600 mb-1">
                                Centro:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idCentro" name="idCentro" value="{{old("idCentro")}}">
                                <option value="">-----</option>
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro['id'] }}" {{ (old("idCentro")== $centro['id'] || app('request')->input('idCentro') == $centro['id'] ? "selected":"") }}>{{ $centro['nombre'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idCentro')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> 
                
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="fechaTomaPosesion" class="block text-sm text-gray-600 mb-1">
                                Toma de posesión:
                            </label>
                            <input id="fechaTomaPosesion" name="fechaTomaPosesion" type="date" value="{{old("fechaTomaPosesion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('fechaTomaPosesion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="fechaCese" class="block text-sm text-gray-600 mb-1">
                                Cese:
                            </label>
                            <input id="fechaCese" name="fechaCese" type="date" value="{{old("fechaCese")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" />
                            @error('fechaCese')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Añadir miembro
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
                            <input type="text" id="search-input" class="text-sm text-gray-600 border py-1 w-full outline-none bg-white px-2 rounded form-input" placeholder="Buscar..." value="{{ request('miembroGobierno') }}">
                        </div>
                    </div>

                </div>
            </div>
 
<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADO ---------------------------------->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($miembrosGobierno as $miembro)
                    <div id="btn-editar-miembro" data-miembro-id="{{ $miembro['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">

                                <div class="flex items-center">
                                    <span class="material-icons-round scale-75">
                                        person
                                    </span>
                                    &nbsp;
                                    <h2 class="text-base font-bold truncate">{{ $miembro->usuario->name }}</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            school
                                        </span>
                                        &nbsp;
                                        <h2 class="truncate">{{ $miembro->centro->nombre }}</h2>
                                    </div>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            psychology
                                        </span>

                                        &nbsp;

                                        @if($miembro->representacion->id == config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'))
                                            @if ($miembro->centro->id == config('constants.TIPOS_CENTRO.FACULTAD')) 
                                                <h2 class="truncate">Decano/a</h2>
                                            @else
                                                <h2 class="truncate">Director/a</h2>
                                            @endif
                                        @elseif ($miembro->representacion->id == config('constants.REPRESENTACIONES.GOBIERNO.VICEDIRECTOR'))
                                            @if ($miembro->centro->id == config('constants.TIPOS_CENTRO.FACULTAD')) 
                                                <h2 class="truncate">ViceDecano/a</h2>
                                            @else
                                                <h2 class="truncate">ViceDirector/a</h2>
                                            @endif
                                        @else
                                            <h2 class="truncate">{{ $miembro->representacion->nombre }}</h2>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            event
                                        </span>
                                        <div class="fechaTomaPosesion truncate">

                                            Toma posesión: {{ $miembro->fechaTomaPosesion }} | 
                                            
                                            @empty ($miembro->fechaCese)
                                                Actualidad
                                            @else
                                                Cese: {{ $miembro->fechaCese }}
                                            @endempty
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($miembro->usuario->hasRole('responsable_centro'))
                                <div class="right-part truncate">
                                    <div class="flex items-center">
                                        <span class="material-icons-round text-yellow-700">
                                            workspace_premium
                                        </span>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                Miembro de Gobierno
                            </span>

                            @if ($miembro['fechaCese']==null)
                                <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                            @else
                                <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

<!----------------------------- END LISTADO ---------------------------------->

        </div>
    </div>
    @endsection

@vite(['resources/js/miembrosGobierno/miembrosGobierno.js'])