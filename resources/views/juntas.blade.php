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

                <div>
                    <div id="btn-add-junta" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                        <span class="material-icons-round scale-75">
                            add_circle
                        </span>
                        Añadir junta
                    </div>
                </div>
            </div>

            <hr class="my-4 border-t border-gray-300" />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @if($juntas && $juntas[0])
                    @foreach ($juntas as $junta)
                        <div id="btn-editar-junta" data-junta-id="{{ $junta['id'] }}" class="card bg-white p-6 rounded-lg shadow-md cursor-pointer">
                            <div class="flex items-center">
                                <div class="right-part w-full max-w-max">
                                    <img src="{{ $junta->centro->logo ? $junta->centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                                </div>

                                <div class="left-part truncate w-full max-w-max pl-3 z-10">

                                    <div class="flex items-center">
                                        <span class="material-icons-round scale-75">
                                            account_balance
                                        </span>
                                        &nbsp;
                                        <h2 class="text-base font-bold truncate">{{ $junta->centro->nombre }}</h2>
                                    </div>

                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            event
                                        </span>
                                        <div class="font-bold fechaTomaPosesion truncate">
                                            {{ $junta->fechaConstitucion }} | {{ $junta->fechaDisolucion ? $junta->fechaDisolucion : 'Actualidad' }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">

                                            @empty($junta->directores[0])
                                                <span class="material-icons-round scale-75">
                                                    person
                                                </span>
                                                <div class="truncate">
                                                    @if($junta->centro->tipo->id == config('constants.TIPOS_CENTRO.FACULTAD'))
                                                        Decano/a:
                                                    @else
                                                        Director/a:
                                                    @endif
                                                    Sin asignar
                                                </div>
                                            @else
                                                @foreach ($junta->directores as $d)
                                                    <span class="material-icons-round scale-75">
                                                        person
                                                    </span>
                                                    <div class="truncate">
                                                        @if($junta->centro->tipo->id == config('constants.TIPOS_CENTRO.FACULTAD'))
                                                            Decano/a:
                                                        @else
                                                            Director/a:
                                                        @endif

                                                        {{ $d->usuario->name }}
                                                    </div>
                                                @endforeach
                                            @endempty
                                        </div>
                                    </div>

                                    <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                        <div class="truncate flex items-center">

                                            @empty($junta->secretarios[0])
                                                <span class="material-icons-round scale-75">
                                                    person
                                                </span>
                                                <div class="truncate">
                                                    Secretario/a: Sin asignar
                                                </div>
                                            @else
                                                @foreach ($junta->secretarios as $s)
                                                    <span class="material-icons-round scale-75">
                                                        person
                                                    </span>
                                                    <div class="truncate">
                                                        Secretario/a: {{ $s->usuario->name }}
                                                    </div>
                                                @endforeach
                                            @endempty
                                        </div>
                                    </div>

                                    <div class="flex justify-start items-center gap-2 mt-3" >
                                        <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">Junta</span>
                                        @if ($junta['fechaDisolucion']==null)
                                            <span class="text-xs bg-green-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Vigente</span>
                                        @else
                                            <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">No vigente</span>
                                        @endif

                                        @if ($junta['estado']==0)
                                            <span class="text-xs bg-red-200 text-blue-900 font-semibold px-2 rounded-lg truncate">Eliminado</span>
                                        @endif
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
    const default_image = "{{asset('img/default_image.png')}}"
</script>
@vite(['resources/js/juntas/juntas.js'])