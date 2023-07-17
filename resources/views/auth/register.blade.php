@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 text-sm">
    <div class="w-full p-1 outline-none bg-slate-100 rounded max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center text-lg font-bold mb-6">{{ __('Register') }}</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="flex gap-4">
                    <div class="w-2/4 mb-4">
                        <label for="name" class="block text-gray-700 font-medium mb-2">{{ __('Name') }}</label>
    
                        <input id="name" type="text" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
    
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <div class="w-4/6 mb-4">
                        <label for="surname" class="block text-gray-700 font-medium mb-2">{{ __('Surname') }}</label>
    
                        <input id="surname" type"text" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('surname') border-red-500 @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>
    
                        @error('surname')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('Email Address') }}</label>

                    <input id="email" type="email" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>

                    <input id="password" type="password" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="block text-gray-700 font-medium mb-2">{{ __('Confirm Password') }}</label>

                    <input id="password-confirm" type="password" class="w-full p-1 outline-none bg-slate-100 rounded form-input" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="mb-6">
                    <button type="submit" class="w-full p-1 outline-none bg-slate-500 text-white font-semibold py-2 px-4 rounded hover:bg-slate-600 transition-all duration-200">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection