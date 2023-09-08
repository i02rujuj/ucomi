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
                    <input type="file" name="imagen" id="imagen" class="form-control-file" placeholder="Seleccione una imagen">

                </div>
            </form>

        </div>
    </div>

@endsection
