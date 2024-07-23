@extends('layouts.app')

@section ('title')
    Información Pública @if($comision!=null) {{$comision->nombre}} @endif
@endsection

@section('contentTop')

    <div class="flex divide-x mb-8 mt-24 items-center justify-evenly w-full">
        <div class=" px-10">
            <img src="{{ asset('img/inicio1.png') }}" alt="LogoUCO" class="rounded-lg w-60 h-28 object-cover transition ease-in-out hover:scale-105" />
        </div>

        <div class="max-lg:hidden px-10 lg:text-2xl text-gray-600 text-center">
            <div>
                Comisión de @if($comision!=null) {{$comision->nombre}} @endif
            </div>
            <div class="text-sm">
                Junta de @if($comision->junta->centro->idTipo!=3) {{$comision->junta->centro->tipo->nombre}} @endif {{$comision->junta->centro->nombre}} 
            </div>
        </div>

        <div class="px-10">
            <img src="{{$comision->junta->centro->logo}}" alt="LogoCentro" class="w-28 h-28 rounded-lg object-cover transition ease-in-out hover:scale-105" />
        </div>
    </div>

    <div class="hidden max-lg:block px-10 text-2xl text-gray-600 text-center">
        <div>
            Comisión de @if($comision!=null) {{$comision->nombre}} @endif
        </div>
        <div class="text-sm">
            Junta de @if($comision->junta->centro->idTipo!=3) {{$comision->junta->centro->tipo->nombre}} @endif {{$comision->junta->centro->nombre}} 
        </div>
    </div>

@endsection

@section('content')
<div class="mx-auto lg:px-36 md:px-10 w-full mb-32 text-gray-600">
    @if($comision!=null)
        <div x-data="{ 
            openTab: 0,
            activeClasses: 'border-l border-t border-r rounded-t text-grey-700 font-semibold',
            inactiveClasses: 'text-grey-500 hover:border-l hover:border-t hover:border-r hover:rounded-t'
        }" class="p-6">

            <ul class="flex justify-start border-b mb-4">
                <li @click="openTab = 0" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1" @click.prevent="tab = 0">
                    <a href="#" :class="openTab === 0 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            send
                        </span>
                        <span class="max-sm:hidden">Información</span>
                    </a>
                </li>
                <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1" @click.prevent="tab = 1">
                    <a href="#" :class="openTab === 1 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            groups
                        </span>
                        <span class="max-sm:hidden">Composición</span>
                    </a>
                </li>
                <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="mr-1" @click.prevent="tab = 2">
                    <a href="#" :class="openTab === 2 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            description
                        </span>
                        <span class="max-sm:hidden">Actas</span>
                    </a>
                </li>
            </ul>

            <div class="w-full">

                {{--INFORMACIÓN--}}
                <div x-show="openTab === 0">
                    <div class="ml-4 text-lg">
                        <div class="ml-4 font-semibold">
                            Comisión de @if($comision!=null) {{$comision->nombre}} @endif
                        </div>
                        <div class="ml-4">
                            Fecha constitución: {{$comision->fechaConstitucion}}
                        </div>
                        <div class="ml-4">
                            Pertenece a la Junta de @if($comision->junta->centro->idTipo!=3) {{$comision->junta->centro->tipo->nombre}} @endif {{$comision->junta->centro->nombre}} con fecha de constitución {{$comision->junta->fechaConstitucion}}
                        </div>

                        <hr class="mt-4">

                        <div class="ml-4 mt-2">
                            {{$comision->descripcion}}
                        </div>
                    </div>
                </div>

                {{--COMPOSICIÓN--}}
                <div x-show="openTab === 1">

                    <div class="ml-4 mb-3">
                        <table class="w-full text-md text-left rtl:text-right text-gray-700 dark:text-gray-500">
                            <thead class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-1 py-1">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-1 py-1">
                                        Representación
                                    </th>
                                    <th scope="col" class="px-1 py-1">
                                        Cargo
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comision->miembros as $miembro)
                                    <tr class="border-b">
                                        <th scope="col" class="font-normal px-1 py-1">
                                            @if($miembro->usuario->image)
                                                <x-imgTooltip text="{{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                            @else
                                                {{$miembro->usuario->name}}
                                            @endif
                                        </th>
                                        <th scope="col" class="font-normal px-1 py-1">
                                            {{$miembro->representacion->nombre}}
                                        </th>
                                        <th scope="col" class="font-normal px-1 py-1">
                                            {{$miembro->cargo}}
                                        </th>
                                    </tr>                      
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--ACTAS--}}
                <div x-show="openTab === 2">
                    <div class="ml-4 mt-5 text-lg">                          
                        @foreach ($comision->convocatorias as $convocatoria)
                        <div class="ml-4 mt-1">
                            <button id="btn-show-acta" data-acta="{{$convocatoria->acta}}" class="rounded-md hover:text-white hover:bg-gray-700 px-2">    
                                <span class="material-icons-round scale-75">
                                    picture_as_pdf
                                </span>
                                Convocatoria {{$convocatoria->tipo->nombre}} | {{$convocatoria->fecha}}
                            </button>  
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-32">
            Actualmente no se encuentra información disponible sobre la comisión seleccionada
        </div>
    @endif
</div>

@endsection

@section('contentBotttom')

@endsection

@vite(['resources/js/publico/info.js'])