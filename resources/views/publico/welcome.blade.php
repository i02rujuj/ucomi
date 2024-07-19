@extends('layouts.app')

@section ('title')
Ucomi
@endsection

@section('content')
    <section class="bg-ucomi bg-cover lg:h-screen md:h-screen py-8">
        <div class="flex items-center justify-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-5/6 w-full">
            <div class="text-center">
                <div class="mt-28">
                    <div class="sentences_together flex flex-col md:flex-row justify-center mb-5">
                        <h2 class="text-xl font-bold text-white mb-4">
                            Obtén información pública de las Juntas de Centro y Comisiones de la Universidad de Córdoba
                        </h2>
                    </div>

                    <form action="{{ route('infoJunta') }}" method="GET">
                        <div class="flex flex-wrap justify-center items-center gap-4">
                            @foreach ($centros as $centro)
                                <button type="submit" name="centro" value="{{$centro->id}}">
                                    <div class='contenedor tooltip'>
                                        <img src="{{ $centro->logo ? $centro->logo : asset('img/default_image.png') }}" alt="Imagen de centro" class="bg-white w-24 h-24 ml-1 mb-1 object-cover logo rounded-md">   
                                        <span class="tooltiptext">{{$centro->tipo->nombre != 'Otro' ? $centro->tipo->nombre : ''}} {{$centro->nombre}}</span>
                                    </div>
                                </button>                      
                            @endforeach
                        </div>
                    </form>

                    <p class="text-white mt-5">
                        Encontrarás información relativa a la composición de cada órgano de representación, convocatorias, actas...
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('contentBottom')

<!-- Sección de beneficios -->
<section class="snap-start bg-gray-100 lg:py-12 pt-12 mb-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center text-lg font-bold text-gray-900 mb-12">
            Beneficios del sistema de gestión de Juntas de Centro y Comisiones
        </h2>
        <div class="flex flex-wrap -mx-4 justify-center items-center">
            <div class="w-full lg:w-2/5 px-4">
                <ul class="text-gray-700 leading-relaxed">
                    <li id="card-1" class="bg-white mb-4 rounded-lg shadow-lg p-4 hover:bg-gray-100 transition ease-in-out hover:scale-105">
                        <span class="font-bold text-gray-900">Gestión eficiente de datos:</span>
                        Centraliza y agiliza la información de Juntas de Centro y Comisiones, mejorando la eficiencia en la obtención de la información.
                    </li>
                    <li id="card-2" class="bg-white mb-4 rounded-lg shadow-lg p-4 hover:bg-gray-100 transition ease-in-out hover:scale-105">
                        <span class="font-bold text-gray-900">Datos de miembros pertenecientes a cada órgano actualizados y consistentes:</span>
                        Facilita la obtención de datos sobre los miembros de cada una de las Juntas de Centro y Comisión de los centros de la UCO.
                    </li>
                    <li id="card-3" class="bg-white mb-4 rounded-lg shadow-lg p-4 hover:bg-gray-100 transition ease-in-out hover:scale-105">
                        <span class="font-bold text-gray-900">Portal de acceso con obtención de certificados:</span>
                        Proporciona diferentes tipos de certificados para los miembros de cada órgano, histórico de Juntas de Centro y Comisiones a las que el usuario ha pertenecido, asistencia a convocatorias,...
                    </li>
                </ul>
            </div>
            <div class="w-full lg:w-3/5 px-4 mb-8">
                    <img src="{{ asset('img/inicio1.png') }}" alt="LogoUCO" class="rounded-lg w-full h-auto object-cover transition ease-in-out hover:scale-105" />
            </div>
        </div>
    </div>
</section>

@endsection