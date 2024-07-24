@extends ('layouts.panel')
@section ('title')
Miembros de Comisión
@endsection

@section ('content')
    <div class="lg:ml-64 mt-14">
        <div class="mx-auto p-6">

            @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
            @endif
            
            @if (session()->has('errors'))
            <div class="my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("errors") }}
            </div>
            @endif

            <div class="flex justify-between mb-4"> 
                @include('components.filtros.miembrosComisionFiltro')

                <div>
                    <div id="btn-add-miembro" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                        <span class="material-icons-round scale-75">
                            add_circle
                        </span>
                        Añadir miembro
                    </div>
                </div>
            </div>

            <div id="modal_add" name="modal_add" class="hidden mt-4">

                <div id='user'>
                    <x-inputSelectModal label="Usuario*" id="idUsuario" entidad="miembro">
                        <option value="">Selecciona un usuario</option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </x-inputSelectModal>
                </div>

                <x-inputSelectModal label="Comisión*" id="idComision" entidad="miembro">
                    <option value="" selected disabled>Selecciona una comisión</option>
                    @foreach ($comisiones as $comision)
                        <option value="{{ $comision->id }}">{{ $comision->nombre }} </option>
                    @endforeach
                </x-inputSelectModal>

                <x-inputSelectModal label="Representación*" id="idRepresentacion" entidad="miembro">
                    <option value="" selected disabled>Selecciona una representación</option>
                    @foreach ($representacionesGeneral as $rep)
                        <option value="{{$rep->id}}">{{$rep->nombre}}</option>
                    @endforeach
                </x-inputSelectModal>

                <div id='select-cargo'>
                    <x-inputSelectModal label="Cargo" id="cargo" entidad="miembro">
                        <optgroup label="Cargos existentes">
                            <option value="Presidente">Presidente</option>
                        </optgroup>
                    </x-inputSelectModal>
                </div>

                <x-inputDateModal label="Toma Posesión*" id="fechaTomaPosesion" entidad="miembro"></x-inputDateModal>
                <x-inputDateModal label="Fecha Cese" id="fechaCese" entidad="miembro"></x-inputDateModal> 
                
                <x-inputSelectModal label="Responsable:" id="responsable" entidad="miembro">
                    <option class="text-center" value="0">No</option>
                    <option class="text-center" value="1">Sí</option>
                </x-inputSelectModal>
            </div>

            <hr className="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($miembrosComision->count())
                    @foreach ($miembrosComision as $miembro)
                        <div id="btn-editar-miembro" data-miembro-id="{{ $miembro['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            
                            <div class="flex justify-start text-center items-center gap-2">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $miembro->usuario->image ? $miembro->usuario->image : asset('img/default_image_profile.jpg') }}" alt="Imagen de usuario" class="shadow-black shadow-sm w-16 h-16 ml-1 mb-1 justify-self-center rounded-md object-cover">  
                                    <img src="{{ $miembro->comision->junta->centro->logo ? $miembro->comision->junta->centro->logo : asset('img/default_image.jpg') }}" alt="Imagen de centro" class="shadow-black shadow-sm -mt-9 w-8 h-8 ml-1 mb-1 justify-self-center rounded-md ">  
                                </div>
                                <h2 class="font-bold">{{ $miembro->usuario->name }}</h2>
                            </div>

                            <div class="flex font-bold truncate items-center gap-1 mt-2">
                                <div class="flex items-center">
                                    <span class="material-icons-round scale-75">
                                        psychology
                                    </span>
                                    <h2 class="ml-1">{{ $miembro->representacion->nombre }}</h2>
                                </div>
                            </div>
                            
                            <div class="flex gap-3">
                                <div class="left-part truncate">

                                    <div class="text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            send
                                        </span>
                                        <h2 class="ml-1 truncate">{{ $miembro->comision->nombre }}</h2>
                                        </div>
                                    </div>

                                    <div class="text-xs text-slate-400 font-medium truncate items-center gap-1">

                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                event
                                            </span>
                                            <div class="ml-1 fechaTomaPosesion truncate">

                                                {{ $miembro->fecha_toma_posesion_format }} | 
                                                
                                                @empty ($miembro->fechaCese)
                                                    Actualidad
                                                @else
                                                    {{ $miembro->fecha_cese_format }}
                                                @endempty
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap justify-end items-center gap-2 mt-2">
                                <span class="flex items-center text-xs bg-blue-100 font-semibold px-2 rounded-lg truncate">
                                    @if ($miembro->responsable==1)
                                        <span class="text-sm material-icons-round text-yellow-700">
                                            workspace_premium
                                        </span>
                                        Responsable de Comisión
                                    @else
                                        Miembro de Comisión
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
                    No se han encontrado miembros de comisión
                @endif
            </div>

            <div class="mt-5">{{$miembrosComision->appends([
                'filtroCentro' => $filtroCentro,
                'filtroJunta' => $filtroJunta,
                'filtroComision' => $filtroComision,
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
    const miembros = @json($miembrosComision)
</script>

@vite(['resources/js/miembrosComision/miembrosComision.js'])