@extends('layouts.app')
@section ('title')
Información Pública del centro 
@endsection

@section('content')

    <div id="equipoGobierno"></div>
    
</section>

<!-- Sección búsqueda centro -->
<section class="bg-ucomi bg-cover lg:h-screen md:h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
        <div class="text-center">
            <div class="mt-20">
                <div class="sentences_together flex flex-col md:flex-row">
                    <h2 class="text-lg font-bold text-white mb-4">
                        Obtén información pública sobre las comisiones de los diferentes centros de la Universidad de Córdoba
                    </h2>
                </div>

                <p class="text-white mb-4">
                    También encontrarás información relativa a la composición del equipo de gobierno, juntas y comisiones de cada centro
                </p>
                
                <form action="{{ route('infoPublica') }}" method="GET">
                    @csrf
                    <select class="text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none required" id="idCentro" name="idCentro" required>
                        @foreach ($centros as $centro)
                            <option value="{{ $centro['id'] }}">{{ $centro['nombre'] }}</option>
                        @endforeach
                    </select>

                    <div class="buttons flex justify-center items-center gap-6 py-4">
                        <a href="" class="md:w-36 w-1/2 inline-block px-6 py-3 font-bold text-white bg-blue-600 rounded-md shadow-md">
                            Buscar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

@endsection

<script src="{{asset('js/orgchart.js')}}"></script>
@vite(['resources/js/publico/info.js'])