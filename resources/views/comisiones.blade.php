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

            <div id="modal_add" name="modal_add" class="hidden">
                
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-2 justify-center items-center">
                    <label for="nombre" class="block text-sm text-gray-600 w-36 pr-6 text-right">Nombre: *</label>
                    <input id="nombre" name="nombre" type="text" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                    <label for="descripcion" class="block text-sm text-gray-600 w-36 pr-6 text-right">Descripción:</label>
                    <input id="descripcion" name="descripcion" type="text" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none"/>
                </div>
                
                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="idJunta" class="block text-sm text-gray-600 w-36 pr-6 text-right">Junta asociada: *</label>
                    <select id="idJunta" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" >
                        <option value="" selected disabled>Selecciona una junta</option>
                        @foreach ($juntas as $junta)
                            <option value="{{$junta->id}}">{{$junta->fecha_constitucion_format}} | {{$junta->centro->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="fechaConstitucion" class="block text-sm text-gray-600 w-36 text-right">Fecha Constitución: *</label>
                    <input type="date" id="fechaConstitucion" class="swal2-input comision text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none">
                </div>

                <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                    <label for="fechaDisolucion" class="block text-sm text-gray-600 w-36 text-right">Fecha Disolución:</label>
                    <input type="date" id="fechaDisolucion" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none">
                </div>     
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($comisiones && $comisiones[0])
                    @foreach ($comisiones as $com)
                        <div id="btn-editar-comision" data-comision-id="{{ $com['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            <div class="flex items-center">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $com->junta->centro->logo ? $com->junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="max-md:w-12 max-md:h-12 w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="left-part truncate w-full max-w-max pl-3 z-10">

                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            send
                                        </span>
                                        &nbsp;
                                        <h2 class="text-base font-bold truncate">{{ $com->nombre }}</h2>
                                    </div>

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
                                                Presidente/a:
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
                 
                            <div class="flex justify-end items-center gap-2 mt-2" >
                                <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg">Comisión de Junta {{$com->junta->fecha_constitucion_format}}</span>
                                @if ($com['fechaDisolucion']==null)
                                    <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                @else
                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                @endif

                                @if ($com['deleted_at']!=null)
                                    <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                @endif
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