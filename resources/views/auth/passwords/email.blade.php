@extends('layouts.app')

@section('content')

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="text-sm bg-white shadow-md rounded p-8 mb-4 mx-6 md:mx-0">
            <div class="text-lg font-bold text-gray-900 mb-4">{{ __('Restablecer contraseña') }}</div>

            @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-1 my-2 rounded relative" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="email">{{ __('Correo electrónico') }}</label>
                    <input class="w-full p-2 outline-none bg-slate-100 rounded form-input @error('email') border-red-500 @enderror" id="email" type="email" placeholder="Correo electrónico" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button class="w-full bg-slate-500 text-white text-sm font-semibold py-2 px-4 rounded hover:bg-slate-600 transition-all duration-200">
                        {{ __('Enviar restablecimiento contraseña') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection