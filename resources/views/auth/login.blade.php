<div class="flex items-center justify-center h-screen bg-gray-100 text-sm">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 mx-6 md:mx-0">
            <div class="text-center">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('Login') }}</h2>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('Email Address') }}</label>

                    <input id="email" type="email" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>

                    <input id="password" type="password" class="w-full p-1 outline-none bg-slate-100 rounded form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input class="form-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="inline-block text-gray-700 font-medium ml-2" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="mb-6 text-center">
                    <button type="submit" class="w-full bg-slate-500 text-white font-semibold mb-2 py-2 px-4 rounded hover:bg-slate-600 transition-all duration-200">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                    <a class="inline-block align-baseline font-medium text-sm text-slate-400 hover:text-slate-800 underline ml-4 transition-all duration-200" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
