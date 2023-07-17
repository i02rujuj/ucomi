@extends('layouts.app')

@section('content')

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="text-sm bg-white shadow-md rounded p-8 mb-4 mx-6 md:mx-0">
            <div class="text-lg font-bold text-gray-900 mb-4">{{ __('Reset Password') }}</div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="email">{{ __('Email Address') }}</label>
                    <input class="w-full p-2 outline-none bg-slate-100 rounded form-input @error('email') border-red-500 @enderror" id="email" type="email" placeholder="Email Address" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="w-full p-2 outline-none bg-slate-100 rounded form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="password-confirm">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="w-full p-2 outline-none bg-slate-100 rounded form-input" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="w-full bg-slate-500 text-white text-sm font-semibold py-2 px-4 rounded hover:bg-slate-600 transition-all duration-200">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
