@extends ('layouts.panel')
@section ('title')
Centros
@endsection

@section ('content')
    <div class="md:ml-64 lg:ml-64 mt-14">
        <div class="mx-auto p-2">

<!----------------------------- START FILTROS ---------------------------------->

            <div class="flex justify-between">
                <div
                    x-data="{
                        open: false,
                        toggle() {
                            if (this.open) {
                                return this.close()
                            }
            
                            this.$refs.button.focus()
                            this.open = true
                        },
                        close(focusAfter) {
                            if (! this.open) return
                            this.open = false
                            focusAfter && focusAfter.focus()
                        }
                    }"
                    x-on:keydown.escape.prevent.stop="close($refs.button)"
                    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                    x-id="['dropdown-button']"
                    class="relative"
                >
                    <!-- Button -->
                    <button
                        x-ref="button"
                        x-on:click="toggle()"
                        :aria-expanded="open"
                        :aria-controls="$id('dropdown-button')"
                        type="button"
                        class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow"
                    >
                        <span class="material-icons-round scale-75">
                            filter_alt
                        </span>
                    </button>
            
                    <!-- Panel -->
                    <div
                        x-ref="panel"
                        x-show="open"
                        x-transition.origin.top.left
                        x-on:click.outside="close($refs.button)"
                        :id="$id('dropdown-button')"
                        style="display: none;"
                        class="absolute left-0 mt-2 w-60 rounded-md bg-white shadow-md z-20"
                    >
                        <form method="GET" action="{{ route('centros') }}">
                            <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-2">
                                <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">

                                    <div class="left-part truncate mb-2">                              
                                        <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1  outline-none" id="filtroTipo" name="filtroTipo">
                                            <option value="">Todos</option>
                                        </select>
                                    </div>

                                    <input id="filtroNombre" name="filtroNombre" type="text" placeholder="Búsqueda" value="{{app('request')->input('filtroNombre')}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none"/>

                                    <button type="submit" value="filtrar" name="action" class="w-full md:w-auto mt-3 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                                        Filtrar
                                    </button>

                                    <button type="submit" value="limpiar" name="action" class="w-full md:w-auto mt-3 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                                        Limpiar
                                    </button>
                                </div>          
                            </div>
                        </form>
                    </div>
                </div>

                <div>
                    <div id="btn-add-centro" type="submit" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-md shadow cursor-pointer">
                        <span class="material-icons-round scale-75">
                            add_circle
                        </span>
                        Añadir centro
                    </div>
                </div>
            </div>

            <hr class="my-4 border-t border-gray-300" />

<!----------------------------- END FILTROS ---------------------------------->

<!----------------------------- START LISTADOS ---------------------------------->

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                @foreach ($centros as $centro)
                    <div id="btn-editar-centro" data-centro-id="{{ $centro['id'] }}" class="card bg-white p-4 rounded-lg shadow-md cursor-pointer">
                        <div class="flex items-start">
                            <div class="right-part w-full max-w-max">
                                <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
                            </div>

                            <div class="left-part truncate w-full max-w-max pl-3 z-10">
                                <div class="flex items-start">
                                    <span class="material-icons-round scale-75">
                                        school
                                    </span>
                                    &nbsp;
                                    <h2 class="text-base font-bold truncate">{{ $centro['nombre'] }}</h2>
                                </div>

                                <div class="flex text-xs text-slate-400 font-medium truncate items-center gap-1">
                                    <div class="truncate flex items-center">
                                        <span class="material-icons-round scale-75">
                                            place
                                        </span>
                                        <div class="direccion truncate">
                                            {{ $centro->direccion }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 mt-2" >
                                    <span class="text-xs bg-blue-100 text-blue-900 font-semibold px-2 rounded-lg truncate">{{ $centro->tipo->nombre }}</span>
                                </div>
                            </div>
                        </div>         
                    </div>
                @endforeach
            </div>

<!----------------------------- END LISTADOS ---------------------------------->

<!----------------------------- START PAGINACIÓN ---------------------------------->

<div class="mt-5">{{$centros->appends([
    'filtroTipo' => $filtroTipo,
    'filtroNombre' => $filtroNombre,
    ])->links()}}</div>

<!----------------------------- END PAGINACIÓN ---------------------------------->
        </div>
    </div>
    @endsection

<script>
    const default_image = "{{asset('img/default_image.png')}}"
</script>
@vite(['resources/js/centros/centros.js'])