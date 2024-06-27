@extends ('layouts.panel')
@section ('title')
Juntas
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
            <div class="errorMessage my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("error") }}
            </div>
            @endif

            <form method="POST" action="{{ route('juntas.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <div class="text-gray-600 font-bold mb-2">
                    Añadir nueva junta
                </div>
                
                @csrf
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idCentro" class="block text-sm text-gray-600 mb-1">
                                ¿A qué centro pertenece?
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" id="idCentro" name="idCentro" required>
                                <option value="">-----</option>
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro['id'] }}">{{ $centro['nombre'] }}</option>
                                @endforeach
                            </select>
                           
                            @error('idCentro')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="fechaConstitucion" class="block text-sm text-gray-600 mb-1">
                                Fecha de Constitución:
                            </label>
                            <input id="fechaConstitucion" name="fechaConstitucion" type="date" value="{{old("fechaConstitucion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('fechaConstitucion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="fechaDisolucion" class="block text-sm text-gray-600 mb-1">
                                Fecha de Disolución:
                            </label>
                            <input id="fechaDisolucion" name="fechaDisolucion" type="date" value="{{old("fechaDisolucion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off"/>
                            @error('fechaDisolucion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="nombreDirector" class="block text-sm text-gray-600 mb-1">
                                Director/Decano 
                                <span class="text-xs text-gray-600 mb-1">(Miembro nato del Equipo de Gobierno del centro)</span>
                            </label>
                            <input type="hidden" id="idDirector" name="idDirector" required/>
                            <input id="nombreDirector" name="nombreDirector" type="text" class="readonly text-sm text-gray-600 border bg-gray-50 rounded-md px-2 py-1 w-full outline-none " autocomplete="off" required/>
                            @error('idDirector')
                                <p id="errorDirector" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="errorDirectorFront" class="text-red-500 text-xs mt-1"></p>
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="nombreSecretario" class="block text-sm text-gray-600 mb-1">
                                Secretario/a
                                <span class="text-xs text-gray-600 mb-1">(Miembro nato del Equipo de Gobierno del centro)</span> 
                            </label>
                            <input type="hidden" id="idSecretario" name="idSecretario" required/>
                            <input  id="nombreSecretario" name="nombreSecretario" type="text" class="readonly text-sm text-gray-600 border bg-gray-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('idSecretario')
                                <p id="errorSecretario" class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div id="errorSecretarioFront"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Añadir Junta
                </button>
            </form>

            <hr className="my-6 border-t border-gray-300" />

<!----------------------------- START FILTROS ---------------------------------->

            <button class="accordion w-full text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">Filtros</button>
            <div class="panel">
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap gap-2">

                    <div class="left-side w-full">
                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md">
                            <span class="block text-sm text-gray-600 mb-1">
                                Texto:
                            </span>
                            <input type="text" id="search-input" class="text-sm text-gray-600 border py-1 w-full outline-none bg-white px-2 rounded form-input" placeholder="Buscar..." value="{{ request('junta') }}">
                        </div>
                    </div>        
                </div>
            </div>

<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADO ---------------------------------->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($juntas as $junta)
                    <div id="btn-editar-junta" data-junta-id="{{ $junta['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">

                                <div class="flex items-center">
                                    <span class="material-icons-round scale-75">
                                        account_balance
                                    </span>
                                    &nbsp;
                                    <h2 class="text-base font-bold truncate">{{ $junta->centro->nombre }}</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            event
                                        </span>
                                        <div class="fechaTomaPosesion truncate">
                                            Contitución: {{ $junta->fechaConstitucion }} | 
                                            
                                            @empty ($junta->fechaDisolucion)
                                                Actualidad
                                            @else
                                                Disolución: {{ $junta->fechaDisolucion }}
                                            @endempty
                                        </div>
                                    </div>
                                </div>

                                @foreach ($junta->directores as $d)
                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                person
                                            </span>
                                            <div class="truncate">
                                                @if($junta->centro->tipo->id == config('constants.TIPOS_CENTRO.FACULTAD'))
                                                    Decano/a:
                                                @else
                                                    Director/a:
                                                @endif

                                                {{ $d->usuario->name }}
                                                
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($junta->secretarios as $s)
                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                person
                                            </span>
                                            <div class="truncate">
                                                Secretario/a:

                                                {{ $s->usuario->name }}
                                                
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <div class="flex items-center gap-2 mt-2" >
                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Junta</span>
                            @if ($junta['fechaDisolucion']==null)
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

@vite(['resources/js/juntas/juntas.js'])