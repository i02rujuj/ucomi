@extends ('layouts.panel')
@section ('title')
Comisiones
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

            <form method="POST" action="{{ route('comisiones.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <h2 class="text-gray-600 font-bold mb-2">Añadir nueva comisión</h2>
                @csrf
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idJunta" class="block text-sm text-gray-600 mb-1">
                                Junta que gestiona la comisión:
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" id="idJunta" name="idJunta" required>
                                <option value="">-----</option>
                                @foreach ($juntas as $junta)
                                    <option value="{{ $junta['id'] }}">{{ $junta->centro->nombre }}</option>
                                @endforeach
                            </select>
                           
                            @error('idJunta')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="nombre" class="block text-sm text-gray-600 mb-1">
                                Nombre:
                            </label>
                            <input id="nombre" name="nombre" type="text" value="{{old("nombre")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div>
                            <label for="descripcion" class="block text-sm text-gray-600 mb-1">
                                Descripción:
                            </label>
                            <input id="descripcion" name="descripcion" type="textarea" value="{{old("descripcion")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('descripcion')
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

                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Añadir Comisión
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

                    <div class="left-side w-full">
                        <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-72">
                            <span class="block text-sm text-gray-600 mb-1">
                                Estado:
                            </span>
                        
                            <button class="truncate w-full md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" id="buscar-habilitado">
                                Habilitado
                            </button>
                            <button class="truncate w-full md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" id="buscar-deshabilitado">
                                Deshabilitado
                            </button>
                        </div>
                    </div>
                </div>
            </div>

<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADO ---------------------------------->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($comisiones as $com)
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <span class="hidden" id="card-status">{{ $com->estado == 1 ? 'Habilitado' : 'Deshabilitado' }}</span>
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">
                                <div class="flex items-center mb-1">
                                    <span class="material-icons-round mt-1 scale-75">
                                        account_balance
                                    </span>
                                    &nbsp;
                                    <h2 class="text-lg font-bold -mb-1 truncate">{{ $com->nombre }}</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium mb-1 truncate items-center gap-1">
                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round mt-1 scale-75">
                                            description
                                        </span>
                                        &nbsp;
                                        <div class="text-sm font-bold -mb-1 truncate">{{ $com->descripcion }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex text-xs text-slate-400 font-medium mb-1 truncate items-center gap-1">
                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            event
                                        </span>
                                        <div class="fechaTomaPosesion truncate">
                                            Contitución: {{ $com->fechaConstitucion }} | 
                                            
                                            @empty ($com->fechaDisolucion)
                                                Actualidad
                                            @else
                                                Disolución: {{ $com->fechaDisolucion }}
                                            @endempty
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right-part -mr-3 truncate">
                                <button type="button" class="truncate text-sm hover:text-black font-medium py-1 mx-3 rounded"
                                    id="btn-editar-comision" data-comision-id="{{ $com['id'] }}" value="{{ $com['estado'] }}">
                                    <span class="material-icons-round text-slate-400 scale-125 truncate">
                                        edit_note
                                    </span>
                                </button>
                            </div>
                        </div>
            
                        <div class="flex items-center gap-3 mb-1" id="btn-delete-comision" data-comision-id="{{ $com['id'] }}"
                            data-estado="{{ $com['estado'] }}">
                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $com->junta->centro->nombre }}</span>
                            @if ($com['estado']==1)
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

@vite(['resources/js/comisiones/comisiones.js'])
@vite(['resources/js/filtros.js'])