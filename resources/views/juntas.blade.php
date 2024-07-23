@extends ('layouts.panel')
@section ('title')
Juntas
@endsection

@section ('content')
    <div class="lg:ml-64 mt-14">
        <div class="mx-auto p-2">

            @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
            @endif
            
            @if (session()->has('errors'))
            <div class="errorMessage my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("errors") }}
            </div>
            @endif
           
            <div class="flex justify-between">
                
                @include('components.filtros.juntasFiltro')

                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro'))
                    <div>
                        <div id="btn-add-junta" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                            <span class="material-icons-round scale-75">
                                add_circle
                            </span>
                            Añadir junta
                        </div>
                    </div>
                @endif
            </div>

            <div id="modal_add" name="modal_add" class="hidden mt-4">
                <x-inputSelectModal label="Centro asociado: *" id="idCentro" entidad="junta">
                    <option value="" selected disabled>Selecciona un centro</option>
                    @foreach ($centros as $centro)
                        <option value="{{$centro->id}}">{{$centro->nombre}}</option>
                    @endforeach
                </x-inputSelectModal>

                <div class="flex flex-wrap justify-center">
                    <div class="w-1/2 max-sm:pr-4">
                        <x-inputDateModal label="Constitución: *" type="date" id="fechaConstitucion" entidad="junta"></x-inputDateModal>
                    </div>
                    <div class="w-1/2">
                        <x-inputDateModal label="Disolución: *" type="date" id="fechaDisolucion" entidad="junta"></x-inputDateModal> 
                    </div>
                </div>
            </div>

            <hr class="my-2 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($juntas && $juntas[0])
                    @foreach ($juntas as $junta)
                        <div id="btn-editar-junta" data-junta-id="{{ $junta['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            
                            <div class="flex justify-start text-center items-center gap-2">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $junta->centro->logo ? $junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-8 h-8 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>
                                <h2 class="font-bold">{{ $junta->centro->nombre }}</h2>
                            </div>

                            <hr class="my-2">
                            
                            <div class="flex items-center">  

                                <div class="w-full truncate"> 
                                    <div class="left-part truncate w-full pl-3 z-10">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                event
                                            </span>
                                            <div class="font-semibold truncate">
                                                Constitución: {{ $junta->fecha_constitucion_format }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                            @if ($junta->centro->idCentro==config('constants.TIPOS_CENTRO.FACULTAD'))
                                                <div class="truncate flex items-center">
                                                    <span class="material-icons-round scale-75">
                                                        person
                                                    </span>
                                                    <div class="truncate">
                                                        Decano:
                                                        @if($decano = $junta->miembros(config('constants.REPRESENTACIONES.JUNTA.DEC'))->first())
                                                            {{ $decano->usuario->name }}
                                                        @else
                                                            Sin asignar
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="truncate flex items-center">
                                                    <span class="material-icons-round scale-75">
                                                        person
                                                    </span>
                                                    <div class="truncate">
                                                        Director:
                                                        @if($director = $junta->miembros(config('constants.REPRESENTACIONES.JUNTA.DIR'))->first())
                                                            {{ $director->usuario->name }}
                                                        @else
                                                            Sin asignar
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                            <div class="truncate flex items-center">
                                                <span class="material-icons-round scale-75">
                                                    person
                                                </span>
                                                <div class="truncate">
                                                    Secretario:
                                                    @if($secretario = $junta->miembros(config('constants.REPRESENTACIONES.JUNTA.SECRE'))->first())
                                                        {{ $secretario->usuario->name }}
                                                    @else
                                                        Sin asignar
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end items-center gap-10 mt-3" >
                                            <div>
                                                <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Junta</span>
                                                @if ($junta['fechaDisolucion']==null)
                                                    <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                                @else
                                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                                @endif
        
                                                @if ($junta['deleted_at']!=null)
                                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex justify-end items-center gap-2" >
                                                <a id="btn-ver-miembros" data-junta-id="{{ $junta['id'] }}" class="group max-w-max absolute flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="{{route('miembrosJunta')}}?filtroJunta={{ $junta['id'] }}&filtroRepresentacion=&filtroVigente=1&filtroEstado=1&action=filtrar">
                                                    <span class="material-icons-round cursor-pointer">
                                                        groups
                                                    </span>
                                                    <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                                        <div class="flex max-w-xs flex-col items-center">
                                                            <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                                <span>Ver Miembros</span>
                                                            </div>
                                                            <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    No se han encontrado juntas
                @endif
            </div>

            <div class="mt-5">{{$juntas->appends([
                'filtroCentro' => $filtroCentro,
                'filtroVigente' => $filtroVigente,
                'filtroEstado' => $filtroEstado,
                'action' => $action,
                ])->links()}}
            </div>

        </div>
    </div>
    @endsection

<script>
    const permitirAcciones = "{{$permitirAcciones}}"
    const juntas = @json($juntas)
</script>

@vite(['resources/js/juntas/juntas.js'])