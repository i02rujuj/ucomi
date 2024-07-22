@extends ('layouts.panel')
@section ('title')
Certificados
@endsection

@section ('content')

    <div class="lg:ml-64 mt-14">
        <div class="mx-auto p-6">

            @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
            @endif
            
            @if (session()->has('error'))
            <div class="my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("error") }}
            </div>
            @endif

            <form id="form_generar_certificado" method="post" action="{{ route('generarCertificado') }}" enctype="multipart/form-data" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                @csrf
                <div class="form-group flex flex-col gap-2">
                    <label for="certificados" class="font-bold text-slate-600">Certificados</label>

                    <hr class="border-t border-slate-200 my-2">
                    
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">

                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="tipoCertificado" class="block text-sm text-gray-600 mb-1">
                                    Tipo de certificado:
                                </label>
                                
                                <select id="tipoCertificado" name="tipoCertificado" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required>
                                    <option value="{{config('constants.TIPOS_CERTIFICADO.ACTUAL')}}">Situación actual</option>
                                    <option value="{{config('constants.TIPOS_CERTIFICADO.HISTORICO')}}">Histórico</option>
                                </select>
                            
                                @error('tipoCertificado')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="representaciones" class="block text-sm text-gray-600 mb-1">
                                    Representaciones:
                                </label>
                                <input type="checkbox" id="representacionCentro" name="representaciones[]"  value="centro" checked> Centro
                                <input type="checkbox" id="representacionJunta" name="representaciones[]" value="junta" checked class="accent-pink-500"> Junta
                                <input type="checkbox" id="representacionComision" name="representaciones[]" value="comision" checked class="accent-yellow-500"> Comisión
                            
                                @error('representaciones')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="opciones" class="block text-sm text-gray-600 mb-1">
                                    Opciones:
                                </label>
                                <input type="checkbox" id="responsable" name="responsable"> Solo los que he ejercido como responsable
                            
                                @error('opciones')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="fechas" class="flex gap-4 hidden">
                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="fechaDesde" class="block text-sm text-gray-600 mb-1">
                                    Desde fecha toma posesión:
                                </label>
                                <input id="fechaDesde" name="fechaDesde" type="date" value="{{old("fechaDesde")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" />
                                @error('fechaDesde')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
    
                        </div>
                        
                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="fechaHasta" class="block text-sm text-gray-600 mb-1">
                                    Hasta:
                                </label>
                                <input id="fechaHasta" name="fechaHasta" type="date" value="{{old("fechaHasta")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" />
                                @error('fechaHasta')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Generar PDF
                </button>
            </form>
        </div>
    </div>

@endsection

@vite(['resources/js/certificados/certificados.js'])
