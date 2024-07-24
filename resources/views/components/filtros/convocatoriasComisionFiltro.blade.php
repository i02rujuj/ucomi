@extends ('layouts.filtros')

@section ('filtro')
    <form method="GET" action="{{ route('convocatoriasComision') }}">
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-2">
            <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">
                
                <div class="left-part truncate mb-2">                              
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroComision" name="filtroComision">
                        <option value="">Filtrar por comisión</option>
                        @foreach ($comisiones as $comision)
                            <option value="{{ $comision['id'] }}" {{ app('request')->input('filtroComision') == $comision['id'] ? "selected":"" }}> {{ $comision->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">   
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroTipo" name="filtroTipo">
                        <option {{app('request')->input('filtroTipo')!=null && app('request')->input('filtroTipo')==0 ? 'selected' : ""}} value="0">Filtrar por tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo['id'] }}" {{ app('request')->input('filtroTipo') == $tipo['id'] ? "selected":"" }}>{{ $tipo->nombre }}</option>
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