@extends ('layouts.panel')
@section ('title')
Miembros de Gobierno
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

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($miembrosGobierno && $miembrosGobierno[0])
                    @foreach ($miembrosGobierno as $miembro)
                        <div id="btn-editar-miembro" data-miembro-id="{{ $miembro['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="right-part w-full max-w-max">
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
                'filtroVigente' => $filtroVigente,
                'filtroEstado' => $filtroEstado,
                'action' => $action,
                ])->links()}}
            </div>

        </div>
    </div>
    @endsection

@vite(['resources/js/miembrosGobierno/miembrosGobierno.js'])