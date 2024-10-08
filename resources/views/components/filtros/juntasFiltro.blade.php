@extends ('layouts.filtros')

@section ('filtro')
    <form method="GET" action="{{ route('juntas') }}">
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-2">
            <div class="mt-2 bg-white px-6 py-4 rounded-lg shadow-md w-full">
                
                <div class="left-part truncate mb-2">                              
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroCentro" name="filtroCentro">
                        <option value="">Filtrar por centro</option>
                        @foreach ($centros as $centro)
                            <option value="{{ $centro['id'] }}" {{ app('request')->input('filtroCentro') == $centro['id'] ? "selected":"" }}>{{ $centro['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">   
                    <select class="w-full text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" id="filtroVigente" name="filtroVigente">
                        <option {{app('request')->input('filtroVigente')!=null && app('request')->input('filtroVigente')==0 ? 'selected' : ""}} value="0">Filtrar por vigencia</option>
                        <option {{app('request')->input('filtroVigente')!=null && app('request')->input('filtroVigente')==1 ? 'selected' : ""}} value="1">Vigentes</option>
                        <option {{app('request')->input('filtroVigente')!=null && app('request')->input('filtroVigente')==2 ? 'selected' : ""}} value="2">No Vigentes</option>
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