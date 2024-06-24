@extends ('layouts.panel')
@section ('title')
Perfil
@endsection

@section ('content')

    <div class="md:ml-64 lg:ml-64 mt-14">
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

            <form method="post" action="{{ route('generarCertificado') }}" enctype="multipart/form-data" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                @csrf
                <div class="form-group flex flex-col gap-2">
                    <label for="certificados" class="font-bold text-slate-600">Mis certificados</label>

                    <hr class="border-t border-slate-200 my-2">
                    
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full gap-6">

                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="idUsuario" class="block text-sm text-gray-600 mb-1">
                                    Tipo de certificado:
                                </label>
                                
                                <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none required" required id="idCertificado" name="idCertificado">
                                    <option value="1">Certificado Situación actual</option>
                                    <option value="2">Certificado de Centros en los que he participado como miembro de equipo de gobierno</option>
                                    <option value="3">Certificado de Juntas a las que he representado</option>
                                    <option value="4">Certificado de Comisiones a las que he pertenecido</option>
                                </select>
                            
                                @error('idUsuario')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="fechaInicio" class="block text-sm text-gray-600 mb-1">
                                    Desde:
                                </label>
                                <input id="fechaInicio" name="fechaInicio" type="date" value="{{old("fechaInicio")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" />
                                @error('fechaInicio')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
    
                        </div>
                        
                        <div class="left-side w-full">
                            <div class="mb-2">
                                <label for="fechaFin" class="block text-sm text-gray-600 mb-1">
                                    Hasta:
                                </label>
                                <input id="fechaFin" name="fechaFin" type="date" value="{{old("fechaFin")}}" class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" autocomplete="off" />
                                @error('fechaFin')
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

            <form method="POST" action="{{ route('perfil.store') }}" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                @csrf
                <h2 class="text-slate-600 font-bold mb-2">Cambiar Contraseña</h2>
                <div class="mb-2">
                    <label for="oldPassword" class="block text-sm text-slate-600 mb-1">
                        Contraseña Actual:
                    </label>
                    <input id="oldPassword" name="oldPassword" type="password" class="text-sm text-slate-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" required>
                    @error('oldPassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="newPassword" class="block text-sm text-slate-600 mb-1">
                        Nueva Contraseña:
                    </label>
                    <input id="newPassword" name="newPassword" type="password" class="text-sm text-slate-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" required>
                    @error('newPassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="confirmPassword" class="block text-sm text-slate-600 mb-1">
                        Confirmar Contraseña:
                    </label>
                    <input id="newPassword_confirmation" name="newPassword_confirmation" type="password" class="text-sm text-slate-600 border bg-blue-50 rounded-md px-2 py-1 w-full outline-none" required>
                    @error('newPassword_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Guardar
                </button>
            </form>

            <form method="post" action="/save_image_perfil" enctype="multipart/form-data" class="bg-white p-8 mb-6 rounded-lg shadow-md">
                @csrf
                <div class="form-group flex flex-col gap-2">
                    <label for="imagen" class="font-bold text-slate-600">Seleccione una imagen:</label>
                    <hr class="border-t border-slate-200 my-2">
                    <img src="{{ Auth::user()->image ? Auth::user()->image : asset('uploads/img/default_image.png') }}" alt="Imagen de perfil" class="w-28 h-28 self-start ml- mb-1 justify-self-center rounded-full object-cover">
                    <input type="file" name="imagen" id="imagen" class="form-control-file" placeholder="Seleccione una imagen">
                </div>
                <button type="submit" class="w-full md:w-auto mt-6 text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded">
                    Guardar
                </button>
            </form>

        </div>
    </div>

@endsection
