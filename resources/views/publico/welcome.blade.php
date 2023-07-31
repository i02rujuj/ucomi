@extends('layouts.app')

@section ('title')
Ucomi
@endsection

@section('contentBottom')

<!-- Sección de beneficios -->
<section class="snap-start bg-gray-100 lg:py-12 pt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center text-lg font-bold text-gray-900 mb-12">
            Beneficios de nuestro sistema de gestión de comisiones
        </h2>
        <div class="flex flex-wrap -mx-4 justify-center items-center">
            <div class="w-full lg:w-2/5 px-4">
                <ul class="text-gray-700 leading-relaxed">
                    <li id="card-1" class="bg-white mb-4 rounded-lg shadow-lg p-4 cursor-pointer hover:bg-gray-100">
                        <span class="font-bold text-gray-900">Gestión eficiente de datos:</span>
                        Centraliza y agiliza la información de juntas de gobierno y comisiones, mejorando la eficiencia en la obtención de la información.
                    </li>
                    <li id="card-2" class="bg-white mb-4 rounded-lg shadow-lg p-4 cursor-pointer hover:bg-gray-100">
                        <span class="font-bold text-gray-900">Datos de miembros de gobierno y miembros de junta actualizados y consistentes:</span>
                        Facilita la obtención de datos sobre los miembros del equipo de gobierno y de sus juntas de cada uno de los centros de la UCO.
                    </li>
                    <li id="card-3" class="bg-white mb-4 rounded-lg shadow-lg p-4 cursor-pointer hover:bg-gray-100">
                        <span class="font-bold text-gray-900">Portal de acceso con obtención de certificados:</span>
                        Proporciona diferentes tipos de certificados, histórico de comisiones a las que el usuario ha pertenecido, asistencia a convocatorias de comisiones,...
                    </li>
                </ul>
            </div>
            <div class="w-full lg:w-3/5 px-4 mb-8">
                <div id="carousel" class="rounded-lg shadow-lg aspect-w-16 aspect-h-9 lg:max-h-96 md:max-h-96 max-h-48 bg-cover overflow-hidden">
                    <img id="carousel-image" src="{{ asset('img/inicio1.png') }}" alt="Beneficio 1" class="rounded-lg w-full h-auto object-cover" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Contacto -->
<section id="Contacto" class="snap-start bg-gray-100 lg:py-12 pt-12">
    @if (isset($success))
    <div class="mb-2 py-1 px-4 text-sm font-medium bg-blue-100 text-slate-700 rounded" role="alert">
        {{$success}}
    </div>
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h2 class="text-center text-lg font-bold text-gray-900 mb-4">
            Contactar
        </h2>

        <form class="bg-white shadow-md rounded-lg p-12 mb-12" action="#" method="GET">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="example@example.com">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Asunto
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="asunto" name="asunto" type="text" placeholder="Asunto">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="message">
                    Mensaje
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="message" name="message" rows="4" placeholder="Escribe tu mensaje aquí"></textarea>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Contactar
                </button>
            </div>
        </form>
    </div>
</section>

@endsection