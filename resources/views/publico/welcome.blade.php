@extends('layouts.app')

@section ('title')
Ucomi
@endsection

@section('contentBottom')

<!-- Sección de beneficios -->
<section class="snap-start bg-gray-100 lg:py-12 pt-12 mb-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center text-lg font-bold text-gray-900 mb-12">
            Beneficios del sistema de gestión de comisiones
        </h2>
        <div class="flex flex-wrap -mx-4 justify-center items-center">
            <div class="w-full lg:w-2/5 px-4">
                <ul class="text-gray-700 leading-relaxed">
                    <li id="card-1" class="bg-white mb-4 rounded-lg shadow-lg p-4 cursor-pointer hover:bg-gray-100">
                        <span class="font-bold text-gray-900">Gestión eficiente de datos:</span>
                        Centraliza y agiliza la información de juntas y comisiones, mejorando la eficiencia en la obtención de la información.
                    </li>
                    <li id="card-2" class="bg-white mb-4 rounded-lg shadow-lg p-4 cursor-pointer hover:bg-gray-100">
                        <span class="font-bold text-gray-900">Datos de miembros de gobierno, junta y comisiones actualizados y consistentes:</span>
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

@endsection