@extends ('layouts.panel')
@section ('title')
Convocatorias Comisión
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
                @include('components.filtros.convocatoriasComisionFiltro')

                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro|junta|comision'))
                    <div>
                        <div id="btn-add-convocatoria" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                            <span class="material-icons-round scale-75">
                                add_circle
                            </span>
                            Añadir convocatoria
                        </div>
                    </div>
                @endif
            </div>

            <div id="modal_add" name="modal_add" class="hidden">
                
                <div class="flex w-full mt-2 justify-center items-center">
                    <label for="idComision" class="block text-sm text-gray-600 w-36 pr-12 text-right">Comisión: *</label>
                    <select id="idComision" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" >
                        <option value="" selected disabled>Selecciona una comisión</option>
                        @foreach ($comisiones as $comision)
                            <option value="{{$comision->id}}">{{$comision->nombre}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex w-full mt-4 justify-center items-center">
                    <label for="idTipo" class="block text-sm text-gray-600 w-36 pr-12 text-right">Tipo: *</label>
                    <select id="idTipo" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" >
                        <option value="" selected disabled>Selecciona un tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex w-full justify-center items-center">
                    <label for="lugar" class="block text-sm text-gray-600 w-36 pr-5 text-right">Lugar: *</label>
                    <input id="lugar" name="lugar" type="text" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>

                <div class="flex w-full justify-center items-center">
                    <label for="fecha" class="block text-sm text-gray-600 w-36 pr-5 text-right">Fecha: *</label>
                    <input id="fecha" name="fecha" type="date" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" />
                </div>

                <div class="flex w-full justify-center items-center">
                    <label for="hora" class="block text-sm text-gray-600 w-36 pr-5 text-right">Hora: *</label>
                    <input id="hora" name="hora" type="time" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>   
                
                <div class="flex w-full justify-center items-center">
                    <label for="acta" class="block text-sm text-gray-600 w-36 pr-5 text-right">Acta:</label>
                    <input id="acta" name="acta" type="file" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" autocomplete="off"/>
                </div> 
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($convocatorias && $convocatorias[0])
                    @foreach ($convocatorias as $convocatoria)
                        <div id="btn-editar-convocatoria" data-convocatoria-id="{{ $convocatoria['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            <div class="flex justify-start items-center">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $convocatoria->comision->junta->centro->logo ? $convocatoria->comision->junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="left-part truncate w-full max-w-max pl-3 z-10">

                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            location_on
                                        </span>
                                        <div class="font-bold truncate">
                                            {{ $convocatoria->lugar }} 
                                        </div>
                                    </div>

                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">
                                            <span class="material-icons-round scale-75">
                                                event
                                            </span>
                                            <div class="font-bold truncate">
                                                {{ $convocatoria->fecha_format }} 
                                            </div>

                                            <span class="material-icons-round scale-75">
                                                schedule
                                            </span>
                                            <div class="font-bold truncate">
                                                {{ \Carbon\Carbon::parse($convocatoria->hora)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="flex items-center text-xs text-slate-400">
                                        <span class="material-icons-round scale-75">
                                            send
                                        </span>
                                        <h2 class="ml-1 font-medium truncate">{{ $convocatoria->comision->nombre }} </h2>
                                    </div>
                                    
                                    <div class="flex justify-end items-center gap-2 mt-3" >
                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Convocatoria Comisión</span>

                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $convocatoria->tipo->nombre }}</span>

                                        @if ($convocatoria['deleted_at']!=null)
                                            <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end items-center gap-2 mt-3 w-" >
                                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro|junta|comision'))
                                    <a id="btn-asistentes" data-convocatoria-id="{{ $convocatoria['id'] }}" class="group max-w-max relative flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="#">
                                        <span class="material-icons-round cursor-pointer">
                                            diversity_3
                                        </span>
                                        <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                            <div class="flex max-w-xs flex-col items-center">
                                                <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                    <span>Ver asistentes</span>
                                                </div>
                                                <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                            </div>
                                        </div>
                                    </a>

                                    <a id="btn-notificar" data-convocatoria-id="{{ $convocatoria['id'] }}" class="group max-w-max relative flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="#">
                                        <span class="material-icons-round cursor-pointer">
                                            forward_to_inbox
                                        </span>
                                        <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                            <div class="flex max-w-xs flex-col items-center">
                                                <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                    <span>Notificar</span>
                                                </div>
                                                <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                            </div>
                                        </div>
                                    </a>

                                @endif
                                @php
                                    $convocado = Auth::user()->convocados->where('idConvocatoria', $convocatoria->id);
                                @endphp

                                @if($convocatoria->acta)
                                    <a id="btn-visualizar-acta" data-convocatoria-id="{{ $convocatoria['id'] }}" data-acta="{{ $convocatoria['acta'] }}" class="group max-w-max relative flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="#">
                                        <span class="material-icons-round cursor-pointer">
                                            picture_as_pdf
                                        </span>
                                        <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                            <div class="flex max-w-xs flex-col items-center">
                                                <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                    <span>Visualizar acta</span>
                                                </div>
                                                <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                            </div>
                                        </div>
                                    </a>  
                                @endif

                                @if($convocado->count())
                                    <a id="btn-confirmarAsistencia" data-convocatoria-id="{{ $convocatoria['id'] }}" class="group max-w-max relative flex flex-col justify-center items-center hover:rounded-md hover:px-2 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="#">
                                        <span class="material-icons-round cursor-pointer @if($convocatoria->convocado(Auth::user()->id)->asiste) text-green-400 @else text-red-400  @endif">
                                            recommend
                                        </span>
                                        <div class="z-50 invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
                                            <div class="flex max-w-xs flex-col items-center">
                                                <div class="rounded bg-gray-900 p-1 text-xs text-center shadow-lg">
                                                    <span>Confirmar/Cancelar asitencia</span>
                                                </div>
                                                <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    No se han encontrado convocatorias
                @endif
            </div>

            <div class="mt-5">{{$convocatorias->appends([
                'filtroComision' => $filtroComision,
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
    const convocatorias = @json($convocatorias)
</script>
@vite(['resources/js/convocatoriasComision/convocatoriasComision.js'])
@vite(['resources/js/convocatoriasComision/confirmarAsistencia.js'])
