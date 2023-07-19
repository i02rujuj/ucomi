@extends ('layouts.panel')
@section ('title')
Equipo de Gobierno
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
                            <label for="idCentro" class="block text-sm text-gray-600 mb-1">
                                Centro a representar:
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

                        <div class="mb-2">
                            <label for="idRepresentacion" class="block text-sm text-gray-600 mb-1">
                                Representación:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idRepresentacion" name="idRepresentacion" value="{{old("idRepresentacion")}}">
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
                    <div class="right-side w-full">
                        <div class="mb-2">
                            <label for="idUsuario" class="block text-sm text-gray-600 mb-1">
                                Usuario:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idUsuario" name="idUsuario" value="{{old("idUsuario")}}">
                                <option value="">-----</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idUsuario')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="fechaTomaPosesion" class="block text-sm text-gray-600 mb-1">
                                Fecha de Inicio:
                            </label>
                            <input id="fechaTomaPosesion" name="fechaTomaPosesion" type="date" value="{{old("fechaTomaPosesion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('fechaTomaPosesion')
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

                    <div class="left-side w-full">
                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-72">
                            <span class="block text-sm text-gray-600 mb-1">
                                Estado:
                            </span>
                        
                            <button class="truncate  md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" id="buscar-habilitado">
                                Habilitado
                            </button>
                            <button class="truncate  md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" id="buscar-deshabilitado">
                                Deshabilitado
                            </button>
                        </div>
                    </div>

                    <div class="right-side w-full"> 

                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">
                            <label for="search-idCentro" class="block text-sm text-gray-600 mb-1">
                                Centro:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" id="search-idCentro" name="search-idCentro">
                                <option value="">-----</option>
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro['id'] }}">{{ $centro['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="right-side w-full">
                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">
                            <label for="search-idUsuario" class="block text-sm text-gray-600 mb-1">
                                Usuario:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" id="search-idUsuario" name="search-idUsuario">
                                <option value="">-----</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>            
                        </div>
                    </div>
                    <div class="right-side w-full">
                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">

                            <label for="idRepresentacion" class="block text-sm text-gray-600 mb-1">
                                Representación:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" id="idRepresentacion" name="idRepresentacion" value="{{old("idRepresentacion")}}">
                                <option value="">-----</option>
                                @foreach ($representacionesGobierno as $rep)
                                    <option value="{{ $rep['id'] }}">{{ $rep['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>  
                    </div>
                </div>
            </div>
 
<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADO ---------------------------------->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($miembrosGobierno as $miembro)
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <span class="hidden" id="card-status">{{ $miembro->estado == 1 ? 'Habilitada' : 'Deshabilitado' }}</span>
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">
                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round mt-1 scale-75">
                                        person
                                    </span>
                                    &nbsp;
                                    <h2 class="text-lg font-bold -mb-1 truncate">{{ $miembro->usuario->name }}</h2>
                                </div>
                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round scale-75">
                                        lan
                                    </span>
                                    &nbsp;
                                    <h2 class="text-sm mb-1 truncate">{{ $miembro->representacion->nombre }}</h2>
                                </div>
                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round scale-75">
                                        school
                                    </span>
                                    &nbsp;
                                    <h2 class="text-sm mb-1 truncate">{{ $miembro->centro->nombre }}</h2>
                                </div>
                                <div class="flex text-xs text-slate-400 font-medium mb-1 truncate items-center gap-1">
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
                            <div class="right-part -mr-3 truncate">
                                <button type="button" class="truncate text-sm hover:text-black font-medium py-1 mx-3 rounded"
                                    id="btn-editar-miembroGobierno" data-miembro-id="{{ $miembro['id'] }}" value="{{ $miembro['estado'] }}">
                                    <span class="material-icons-round text-slate-400 scale-125 truncate">
                                        edit_note
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-1" id="btn-delete-junta" data-miembro-id="{{ $miembro['id'] }}"
                            data-estado="{{ $miembro['estado'] }}">
                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Miembro Equipo Gobierno</span>
                            @if ($miembro['estado']==1)
                                <span class="material-icons-round text-green-500 scale-150 cursor-pointer">
                                    toggle_on
                                </span>
                            @else
                                <span class="material-icons-round text-slate-400 scale-150 cursor-pointer">
                                    toggle_off
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

<!----------------------------- END LISTADO ---------------------------------->

        </div>
    </div>
    @endsection


@vite(['resources/js/filtros.js'])