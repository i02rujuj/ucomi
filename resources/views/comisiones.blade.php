@extends ('layouts.panel')
@section ('title')
Comisiones
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
            <div class="errorMessage my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("errors") }}
            </div>
            @endif

            <div class="flex justify-between">
                @include('components.filtros.comisionesFiltro')

                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro|junta'))
                    <div>
                        <div id="btn-add-comision" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                            <span class="material-icons-round scale-75">
                                add_circle
                            </span>
                            Añadir comisión
                        </div>
                    </div>
                @endif
            </div>

            <div id="modal_add" name="modal_add" class="hidden mt-4">

                <x-inputModal label="Nombre*" type="text" id="nombre" entidad="comision"></x-inputModal>
                <x-inputModal label="Descripción*" type="text" id="descripcion" entidad="comision"></x-inputModal>

                <x-inputSelectModal label="Junta asociada*" id="idJunta" entidad="comision">
                    <option value="" selected disabled>Selecciona una junta</option>
                    @foreach ($juntas as $junta)
                        <option value="{{$junta->id}}">{{$junta->fecha_constitucion_format}} | {{$junta->centro->nombre}}</option>
                    @endforeach
                </x-inputSelectModal>

                <x-inputDateModal label="Constitución*" id="fechaConstitucion" entidad="comision"></x-inputDateModal>
                <x-inputDateModal label="Disolución" id="fechaDisolucion" entidad="comision"></x-inputDateModal> 

            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($comisiones && $comisiones[0])
                    @foreach ($comisiones as $com)
                        <div id="btn-editar-comision" data-comision-id="{{ $com['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            
                            <div class="flex justify-start text-center items-center gap-2">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $com->junta->centro->logo ? $com->junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-8 h-8 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>
                                <h2 class="font-bold">{{ $com->nombre }}</h2>
                            </div>

                            <hr class="my-2">
                               
                            <div class="flex items-center">

                                <div class="left-part truncate w-full pl-3 z-10">

                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            event
                                        </span>
                                        <div class="font-semibold truncate">
                                            Constitución: {{ $com->fecha_constitucion_format}}
                                        </div>
                                    </div>
                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                person
                                            </span>
                                            <div class="truncate">
                                                Presidente:
                                                @if($presidente = $com->presidente->first())
                                                    {{ $presidente->usuario->name }}
                                                @else
                                                    Sin asignar
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                 
                            <div class="flex justify-between items-center gap-2 mt-2" >

                                <div class="flex flex-wrap gap-2">
                                    <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg">Comisión Junta {{$com->junta->fecha_constitucion_format}}</span>
                                    @if ($com['fechaDisolucion']==null)
                                        <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                    @else
                                        <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                    @endif

                                    @if ($com['deleted_at']!=null)
                                        <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                    @endif
                                </div>

                                <div class="flex justify-end items-center gap-2" >
                                    <a id="btn-ver-miembros" data-comision-id="{{ $com['id'] }}" class="group max-w-max absolute flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="{{route('miembrosComision')}}?filtroCentro=&filtroJunta=&filtroComision={{$com['id']}}&filtroRepresentacion=&filtroVigente=1&filtroEstado=1&action=filtrar">
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
                    @endforeach
                @else
                    No se han encontrado comisiones
                @endif
            </div>

            <div class="mt-5">{{$comisiones->appends([
                'filtroCentro' => $filtroCentro,
                'filtroJunta' => $filtroJunta,
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
    const comisiones = @json($comisiones)
</script>
@vite(['resources/js/comisiones/comisiones.js'])