@extends ('layouts.filtros')

@section ('filtro')
    <form method="GET" action="{{ route('centros') }}">
        <div class="flex gap-2">
            <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">

                <div class="mb-2">
                    <input id="filtroNombre" name="filtroNombre" type="text" placeholder="Filtrar por nombre" value="{{app('request')->input('filtroNombre')}}" class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none"/>
                </div>

                <div class="mb-2">   
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroTipo" name="filtroTipo">
                        <option value="">Filtrar por tipo</option>
                        @foreach ($tiposCentro as $tipo)
                            <option value="{{ $tipo['id'] }}" {{ app('request')->input('filtroTipo') == $tipo['id'] ? "selected":"" }}>{{ $tipo['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">   
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroEstado" name="filtroEstado">
                        <option {{app('request')->input('filtroEstado')!=null && app('request')->input('filtroEstado')==1 ? 'selected' : ""}} value="1">Activos</option>
                        <option {{app('request')->input('filtroEstado')!=null && app('request')->input('filtroEstado')==0 ? 'selected' : ""}} value="0">Eliminados</option>
                        <option {{app('request')->input('filtroEstado')!=null && app('request')->input('filtroEstado')==2 ? 'selected' : ""}} value="2">Todos</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <button type="submit" value="filtrar" name="action" class="text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                        Filtrar
                    </button>

                    <button type="submit" value="limpiar" name="action" class="text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                        Limpiar
                    </button>
                </div>
            </div>          
        </div>
    </form>
@endsection