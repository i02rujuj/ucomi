@extends ('layouts.panel')
@section ('title')
Convocatorias
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

            <form method="POST" action="{{ route('convocatoriasJunta.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                <div class="text-gray-600 font-bold mb-2">
                    Añadir nueva convocatoria de junta
                </div>
                
                @csrf
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">
                    
                    @hasrole(['admin', 'responsable_centro', 'responsable_junta'])
                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idJunta" class="block text-sm text-gray-600 mb-1">
                                Convocatoria de junta                            
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" id="idJunta" name="idJunta">
                                <option value="">-----</option>
                                @foreach ($juntas as $junta)
                                    <option value="{{ $junta['id'] }}" {{ (old("idJunta")== $junta['id'] || app('request')->input('idJunta') == $junta['id'] ? "selected":"") }}>{{ $junta->fechaConstitucion }} - {{ $junta->centro->nombre }}</option>
                                @endforeach
                            </select>
                           
                            @error('idJunta')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endhasrole

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="idtipo" class="block text-sm text-gray-600 mb-1">
                                Tipo
                            </label>
                            
                            <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" id="idTipo" name="idTipo" required>
                                <option value="">-----</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo['id'] }}" {{ (old("idTipo")== $tipo['id'] || app('request')->input('idTipo') == $tipo['id'] ? "selected":"") }}>{{ $tipo['nombre'] }}</option>
                                @endforeach
                            </select>
                        
                            @error('idTipo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
 
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="lugar" class="block text-sm text-gray-600 mb-1">
                                Lugar:
                            </label>
                            <input id="lugar" name="lugar" type="text" value="{{old("lugar")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('lugar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="fecha" class="block text-sm text-gray-600 mb-1">
                                Fecha:
                            </label>
                            <input id="fecha" name="fecha" type="date" value="{{old("fecha")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('fecha')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="hora" class="block text-sm text-gray-600 mb-1">
                                Hora:
                            </label>
                            <input id="hora" name="hora" type="time" value="{{old("hora")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" required/>
                            @error('hora')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="left-side w-full">
                        <div class="mb-2">
                            <label for="acta" class="block text-sm text-gray-600 mb-1">
                                Acta:
                            </label>
                            <input id="acta" name="acta" type="file" value="{{old("acta")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off"/>
                            @error('acta')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                </div>

                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Añadir Convocatoria
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
                @foreach ($convocatorias as $convocatoria)
                    <div id="btn-editar-convocatoria" data-junta-id="{{ $convocatoria['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="left-part truncate">
                                
                                <div class="flex items-center">
                                    <span class="material-icons-round scale-75">
                                        account_balance
                                    </span>
                                    &nbsp;
                                    <h2 class="text-base font-bold truncate">{{ $convocatoria->junta->centro->nombre }}</h2>
                                </div>

                                <div class="flex items-center">
                                    &nbsp;
                                    <h2 class="text-base font-bold truncate">({{ $convocatoria->junta->fechaConstitucion }})</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            place
                                        </span>
                                        &nbsp;
                                        <h2 class="truncate">{{ $convocatoria->lugar }}</h2>
                                    </div>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="flex items-center ">
                                        <span class="material-icons-round scale-75">
                                            schedule
                                        </span>
                                        &nbsp;
                                        <h2 class="truncate">Fecha: {{ $convocatoria->fecha }} | Hora: {{ $convocatoria->hora }}</h2>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center gap-2 mt-2" >
                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Convocatoria de {{$convocatoria['idJunta']==null ? 'Comisión' : 'Junta'}}</span>
                            
                            <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{$convocatoria->tipo->nombre}}</span>
                            
                            @if ($convocatoria['fecha']>now())
                                <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Pendiente</span>
                            @else
                                <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Realizada</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

<!----------------------------- END LISTADO ---------------------------------->

        </div>
    </div>
    @endsection

@vite(['resources/js/convocatorias/convocatorias.js'])
@vite(['resources/js/filtros.js'])