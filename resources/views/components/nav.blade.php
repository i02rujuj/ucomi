<nav class="bg-white fixed top-0 w-full z-50">
    <div class="mx-auto max-w-full sm:px-4 lg:px-8">
        <div class="flex items-center justify-between h-20 ml-2">
                <div class="flex transition-all duration-200 ease-in-out">
                    <a href="{{ route('welcome') }}">
                        <img class="h-12" src="{{ asset('img/logo.png') }}" alt="logo_ucomi" />
                    </a>
                </div> 
                           
            <div class="md:block">
                <div class="ml-4 flex items-center md:ml-6"> 
                    @if (!Auth::check())
                        <a href="{{ route('login') }}" class="ml-3 px-3 py-1 text-sm font-medium rounded-md hover:text-white hover:bg-gray-700">Iniciar sesión</a>
                    @else
                        <a href="{{ route('home') }}" class="ml-3 px-3 py-1 text-sm font-medium rounded-md hover:text-white hover:bg-gray-700">{{Auth::user()->name}}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>