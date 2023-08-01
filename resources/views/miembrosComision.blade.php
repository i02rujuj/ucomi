@extends ('layouts.panel')
@section ('title')
Miembros de Comisión
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

            <form method="POST" action="{{ route('miembrosComision.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <h2 class="text-gray-600 font-bold mb-2">Añadir nuevo miembro de Comisión</h2>
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
                                @foreach ($representacionesGeneral as $rep)
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
                            <label for="idComision" class="block text-sm text-gray-600 mb-1">
                                Comisión:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idComision" name="idComision" value="{{old("idComision")}}">
                                <option value="">-----</option>
                                @foreach ($comisiones as $com)
                                    <option value="{{ $com['id'] }}" {{ (old("idComision")== $com['id'] || app('request')->input('idComision') == $com['id'] ? "selected":"") }}>{{ $com->nombre }}</option>
                                @endforeach
                            </select>
                        
                            @error('idComision')
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
                @foreach ($miembrosComision as $miembro)
                    <div id="btn-editar-miembro" data-miembro-id="{{ $miembro['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                        <span class="hidden" id="card-status">{{ $miembro->estado == 1 ? 'Habilitado' : 'Deshabilitado' }}</span>
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">

                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round mt-1 scale-75">
                                        person
                                    </span>
                                    &nbsp;
                                    <h2 class="text-lg font-bold mb-1 truncate">{{ $miembro->usuario->name }}</h2>
                                </div>

                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round scale-75">
                                        send
                                    </span>
                                    &nbsp;
                                    <h2 class="text-sm mb-1 truncate">{{ $miembro->comision->nombre }}</h2>
                                </div>

                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round scale-75">
                                        psychology
                                    </span>
                                    &nbsp;
                                    <h2 class="text-sm mb-1 truncate">{{ $miembro->representacion->nombre }}</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium mb-2 truncate items-center gap-1">
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
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs bg-blue-100 {{ $miembro->comision->fechaDisolucion == null ? 'bg-blue-100' : 'bg-red-200' }} font-semibold px-2 rounded-lg truncate">
                                Miembro de Comisión
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

@vite(['resources/js/miembrosComision/miembrosComision.js'])
@vite(['resources/js/filtros.js'])