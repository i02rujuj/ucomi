@extends ('layouts.panel')
@section ('title')
Convocatorias Junta
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
                @include('components.filtros.convocatoriasJuntaFiltro')

                @if($permitirAcciones = Auth::user()->esResponsable('admin|centro|junta'))
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
                
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-2 justify-center items-center">
                    <label for="idJunta" class="block text-sm text-gray-600 w-36 pr-12 text-right">Junta: *</label>
                    <select id="idJunta" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" >
                        <option value="" selected disabled>Selecciona una junta</option>
                        @foreach ($juntas as $junta)
                            <option value="{{$junta->id}}">{{$junta->fechaConstitucion}} | {{$junta->centro->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-4 justify-center items-center">
                    <label for="idTipo" class="block text-sm text-gray-600 w-36 pr-12 text-right">Tipo: *</label>
                    <select id="idTipo" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" >
                        <option value="" selected disabled>Selecciona un tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="lugar" class="block text-sm text-gray-600 w-36 pr-5 text-right">Lugar: *</label>
                    <input id="lugar" name="lugar" type="text" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="fecha" class="block text-sm text-gray-600 w-36 pr-5 text-right">Fecha: *</label>
                    <input id="fecha" name="fecha" type="date" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" />
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="hora" class="block text-sm text-gray-600 w-36 pr-5 text-right">Hora: *</label>
                    <input id="hora" name="hora" type="time" class="convocatoria swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>   
                
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
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
                                    <img src="{{ $convocatoria->junta->centro->logo ? $convocatoria->junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="left-part truncate w-full max-w-max pl-3 z-10">

                                    {{--<div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            send
                                        </span>
                                        &nbsp;
                                        <h2 class="text-base font-bold truncate">{{ $convocatoria->junta->fechaConstitucion }} | {{ $convocatoria->junta->centro->nombre }}</h2>
                                    </div>--}}

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
                                                {{ $convocatoria->fecha }} 
                                            </div>

                                            <span class="material-icons-round scale-75">
                                                schedule
                                            </span>
                                            <div class="font-bold truncate">
                                                {{ \Carbon\Carbon::parse($convocatoria->hora)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div> 
                                    
                                    <div class="flex justify-end items-center gap-2 mt-3" >
                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Convocatoria Junta</span>

                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $convocatoria->tipo->nombre }}</span>

                                        @if ($convocatoria['deleted_at']!=null)
                                            <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end items-center gap-2 mt-3" >
                                <span id="notificar" name="notificar" data-convocatoria-id="{{ $convocatoria['id'] }}" class="material-icons-round cursor-pointer">
                                    forward_to_inbox
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    No se han encontrado convocatorias
                @endif
            </div>

            <div class="mt-5">{{$convocatorias->appends([
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
    const convocatorias = @json($convocatorias);
</script>
@vite(['resources/js/convocatoriasJunta/convocatoriasJunta.js'])