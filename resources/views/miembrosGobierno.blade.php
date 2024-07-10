@extends ('layouts.panel')
@section ('title')
Miembros de Centro
@endsection

@section ('content')
    <div class="lg:ml-64 mt-14">
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

            <div class="flex justify-between"> 
                @include('components.filtros.miembrosFiltro')

                <div>
                    <div id="btn-add-miembro" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                        <span class="material-icons-round scale-75">
                            add_circle
                        </span>
                        Añadir miembro
                    </div>
                </div>
            </div>

            <div id="modal_add" name="modal_add" class="hidden">

                <div id='user'>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-4 justify-center items-center">
                        <label for="idUsuario" class="block text-sm text-gray-600 w-36 pr-6 text-right">Usuario: *</label>
                        <select id="idUsuario" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                            <option value="">Selecciona un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-4 justify-center items-center">
                    <label for="idCentro" class="block text-sm text-gray-600 mb-1 w-36 pr-6 text-right">Centro asociado: *</label>
                    <select id="idCentro" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                        <option value="" selected disabled>Selecciona un centro</option>
                        @foreach ($centros as $centro)
                            <option value="{{$centro->id}}">{{$centro->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-4 justify-center items-center">
                    <label for="idRepresentacion" class="block text-sm text-gray-600 w-36 pr-6 text-right">Representación: *</label>
                    <select id="idRepresentacion" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                        <option value="" selected disabled>Selecciona una representación</option>
                        @foreach ($representacionesGobierno as $rep)
                            <option value="{{$rep->id}}">{{$rep->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="fechaTomaPosesion" class="block text-sm text-gray-600 w-36 text-right">Toma posesión: *</label>
                    <input type="date" id="fechaTomaPosesion" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none">
                </div>
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="fechaCese" class="block text-sm text-gray-600 w-36 text-right">Fecha cese:</label>
                    <input type="date" id="fechaCese" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none">
                </div>
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-3 justify-center items-center">
                    <label for="responsable" class="block text-sm text-gray-600 w-36 pr-6 text-right">Responsable:</label>
                    <select id="responsable" class="miembro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none">                     
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>        
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($miembrosGobierno && $miembrosGobierno[0])
                    @foreach ($miembrosGobierno as $miembro)
                        <div id="btn-editar-miembro" data-miembro-id="{{ $miembro['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="right-part w-full max-w-max items-center">
                                    <img src="{{ $miembro->centro->logo ? $miembro->centro->logo : asset('img/default_image.jpg') }}" alt="Imagen de centro" class="w-8 h-8 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                    <img src="{{ $miembro->usuario->image ? $miembro->usuario->image : asset('img/default_image_profile.jpg') }}" alt="Imagen de usuario" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>
                            
                                <div class="left-part truncate">
                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            person
                                        </span>
                                        &nbsp;
                                        <h2 class="text-base font-bold truncate">{{ $miembro->usuario->name }}</h2>
                                    </div>

                                    <div class="flex font-bold truncate items-center gap-1">
                                        <div class="flex items-center">
                                            <span class="material-icons-round scale-75">
                                                psychology
                                            </span>

                                            &nbsp;

                                            @if($miembro->representacion->id == config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'))
                                                @if ($miembro->centro->id == config('constants.TIPOS_CENTRO.FACULTAD')) 
                                                    <h2 class="">Decano/a</h2>
                                                @else
                                                    <h2 class="">Director/a</h2>
                                                @endif
                                            @elseif ($miembro->representacion->id == config('constants.REPRESENTACIONES.GOBIERNO.VICEDIRECTOR'))
                                                @if ($miembro->centro->id == config('constants.TIPOS_CENTRO.FACULTAD')) 
                                                    <h2 class="">ViceDecano/a</h2>
                                                @else
                                                    <h2 class="">ViceDirector/a</h2>
                                                @endif
                                            @else
                                                <h2 class="">{{ $miembro->representacion->nombre }}</h2>
                                            @endif
                                        </div>
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
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                event
                                            </span>
                                            <div class="fechaTomaPosesion truncate">

                                                {{ $miembro->fechaTomaPosesion }} | 
                                                
                                                @empty ($miembro->fechaCese)
                                                    Actualidad
                                                @else
                                                    {{ $miembro->fechaCese }}
                                                @endempty
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end items-center gap-2 mt-4">
                                <span class="flex items-center text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                    @if ($miembro->usuario->hasRole('responsable_centro'))
                                    <span class="material-icons-round text-yellow-700">
                                        workspace_premium
                                    </span>
                                    
                                    Responsable de Centro
                                    @else
                                        Miembro de Centro
                                    @endif
                                </span>
                            
                                @if ($miembro['fechaCese']==null)
                                    <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                @else
                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                @endif

                                @if ($miembro['deleted_at']!=null)
                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    No se han encontrado miembros de centro
                @endif
            </div>

            <div class="mt-5">{{$miembrosGobierno->appends([
                'filtroCentro' => $filtroCentro,
                'filtroRepresentacion' => $filtroRepresentacion,
                'filtroVigente' => $filtroVigente,
                'filtroEstado' => $filtroEstado,
                'action' => $action,
                ])->links()}}
            </div>

        </div>
    </div>
    @endsection

<script>
    const miembros = @json($miembrosGobierno)
</script>
@vite(['resources/js/miembrosGobierno/miembrosGobierno.js'])