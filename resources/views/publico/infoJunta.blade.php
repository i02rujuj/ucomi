@extends('layouts.app')

@section ('title')
    Información Pública @if($centro!=null) {{$centro->tipo->nombre}} {{$centro->nombre}} @endif
@endsection

@section('contentTop')

    <div class="flex divide-x mb-4 mt-24 items-center justify-evenly w-full">
        <div class="px-10">
            <img src="{{ asset('img/inicio1.png') }}" alt="LogoUCO" class="rounded-lg w-60 h-28 object-cover transition ease-in-out hover:scale-105" />
        </div>

        <div class="max-lg:hidden px-10 lg:text-2xl text-gray-600 text-center">
            Junta de @if($centro->idTipo!=config('constants.TIPOS_CENTRO.OTRO')) {{$centro->tipo->nombre}} @endif {{$centro->nombre}}
        </div>

        <div class="px-10">
            <img src="{{$centro->logo}}" alt="LogoCentro" class="w-28 h-28 rounded-lg object-cover transition ease-in-out hover:scale-105" />
        </div>
    </div>

    <div class="hidden max-lg:block px-10 text-2xl text-gray-600 text-center my-8">
        Junta de @if($centro->idTipo!=config('constants.TIPOS_CENTRO.OTRO')) {{$centro->tipo->nombre}} @endif {{$centro->nombre}}
    </div>

@endsection

@section('content')
<div class="mx-auto lg:px-36 md:px-10 w-full mb-32 text-gray-600">
    @if($junta!=null)
        <div x-data="{ 
            openTab: 0,
            activeClasses: 'border-l border-t border-r rounded-t text-grey-700 font-semibold',
            inactiveClasses: 'text-grey-500 hover:border-l hover:border-t hover:border-r hover:rounded-t'
        }" class="p-6">

            <ul class="flex justify-start border-b mb-4">
                <li @click="openTab = 0" :class="{ '-mb-px': openTab === 0 }" class="-mb-px mr-1" @click.prevent="tab = 0">
                    <a href="#" :class="openTab === 0 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            workspaces
                        </span>
                        <span class="max-sm:hidden">Información</span>
                    </a>
                </li>
                <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1" @click.prevent="tab = 1">
                    <a href="#" :class="openTab === 1 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            account_balance
                        </span>
                        <span class="max-sm:hidden">Equipo de Gobierno</span>
                    </a>
                </li>
                <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="-mb-px mr-1" @click.prevent="tab = 2">
                    <a href="#" :class="openTab === 2 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            groups
                        </span>
                        <span class="max-sm:hidden">Composición</span>
                    </a>
                </li>
                <li @click="openTab = 3" :class="{ '-mb-px': openTab === 3 }" class="mr-1" @click.prevent="tab = 3">
                    <a href="#" :class="openTab === 3 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            description
                        </span>
                        <span class="max-sm:hidden">Actas</span>
                    </a>
                </li>
                <li @click="openTab = 4" :class="{ '-mb-px': openTab === 4 }" class="mr-1" @click.prevent="tab = 4">
                    <a href="#" :class="openTab === 4 ? activeClasses : inactiveClasses"
                        class="flex gap-2 bg-white py-2 px-4">
                        <span class="material-icons-round">
                            send
                        </span>
                        <span class="max-sm:hidden">Comisiones</span>
                    </a>
                </li>
            </ul>

            <div class="w-full">

                {{--INFORMACIÓN--}}
                <div x-show="openTab === 0">
                    <div class="ml-4 text-lg">
                        <div class="ml-4 mt-2 font-semibold">
                            Junta de @if($junta->centro->idTipo!=config('constants.TIPOS_CENTRO.OTRO')) {{$junta->centro->tipo->nombre}} @endif {{$junta->centro->nombre}}
                        </div>
                        <div class="ml-4 mt-2">
                            - Fecha constitución: {{$junta->fechaConstitucion}}
                        </div>
                    </div>
                </div>

                {{--EQUIPO GOBIERNO--}}
                <div x-show="openTab === 1">
                    <div class="ml-4">
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Director/a
                            </div>
                            @foreach ($junta->centro->miembros(config('constants.REPRESENTACIONES.GOBIERNO.DIR'))->get() as $miembro)  
                                @if($miembro->usuario->image)
                                    <div class="ml-4">
                                        <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                    </div>
                                @else
                                    <div class="ml-4">
                                        <p class="">- {{$miembro->usuario->name}}</p>
                                    </div>
                                @endif
                                @if ($miembro->cargo)
                                     <div class="text-sm font-semibold">
                                        <pre>    {{$miembro->cargo}}</pre>
                                    </div>
                                @endif       
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Secretario/a
                            </div>
                            @foreach ($junta->centro->miembros(config('constants.REPRESENTACIONES.GOBIERNO.SECRE'))->get() as $miembro)
                                @if($miembro->usuario->image)
                                    <div class="ml-4">
                                        <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                    </div>
                                @else
                                    <div class="ml-4">
                                        <p class="">- {{$miembro->usuario->name}}</p>
                                    </div>
                                @endif  
                                @if ($miembro->cargo)
                                    <div class="text-sm font-semibold">
                                        <pre>    {{$miembro->cargo}}</pre>
                                    </div>
                                @endif                     
                            @endforeach
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Subdirectores
                            </div>
                            @foreach ($junta->centro->miembros(config('constants.REPRESENTACIONES.GOBIERNO.SUBDIR'))->get() as $miembro)
                                @if($miembro->usuario->image)
                                    <div class="ml-4">
                                        <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                    </div>
                                @else
                                    <div class="ml-4">
                                        <p class="">- {{$miembro->usuario->name}}</p>
                                    </div>
                                @endif 
                                @if ($miembro->cargo)
                                    <div class="text-sm font-semibold">
                                        <pre>    {{$miembro->cargo}}</pre>
                                    </div>
                                @endif           
                            @endforeach
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Designados por el director
                            </div>
                            @foreach ($junta->centro->miembros(config('constants.REPRESENTACIONES.JUNTA.LIBRE'))->get() as $miembro)
                                @if($miembro->usuario->image)
                                    <div class="ml-4">
                                        <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                    </div>
                                @else
                                    <div class="ml-4">
                                        <p class="">- {{$miembro->usuario->name}}</p>
                                    </div>
                                @endif    
                                @if ($miembro->cargo)
                                    <div class="text-sm font-semibold">
                                        <pre>    {{$miembro->cargo}}</pre>
                                    </div>
                                @endif                   
                            @endforeach                           
                        </div>
                    </div>
                </div>

                {{--COMPOSICIÓN--}}
                <div x-show="openTab === 2">
                    <div class="ml-4">
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Director/a
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.DIR'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Secretario/a
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.SECRE'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif                       
                                @endforeach
                            </div>
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Representantes de profesorado con vinculación permanente adscritos al Centro
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.PDI_VP'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif                       
                                @endforeach
                            </div>
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Representantes de Personal Docente e Investigador adscrito al Centro
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.PDI'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif                        
                                @endforeach
                            </div>
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Representantes del Personal de Administración y Servicios
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.PAS'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif                        
                                @endforeach
                            </div>
                        </div>
            
                        <div class="mb-3">
                            <div class="text-md font-bold">
                                Representantes de los Estudiantes del titulos oficiales tutelados por el Centro
                            </div>
                            <div>
                                @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.EST'))->get() as $miembro)
                                    @if($miembro->usuario->image)
                                        <div class="ml-4">
                                            <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                        </div>
                                    @else
                                        <div class="ml-4">
                                            <p class="">- {{$miembro->usuario->name}}</p>
                                        </div>
                                    @endif                       
                                @endforeach
                            </div>
                        </div>
            
                        <div class="text-md font-bold">
                            Designados por el director
                        </div>
                        <div>
                            @foreach ($junta->miembros(config('constants.REPRESENTACIONES.JUNTA.LIBRE'))->get() as $miembro)
                                @if($miembro->usuario->image)
                                    <div class="ml-4">
                                        <x-imgTooltip text="- {{$miembro->usuario->name}}" image="{{$miembro->usuario->image}}" />
                                    </div>
                                @else
                                    <div class="ml-4">
                                        <p class="">- {{$miembro->usuario->name}}</p>
                                    </div>
                                @endif                        
                            @endforeach
                        </div>
                    </div>
                </div>

                {{--ACTAS--}}
                <div x-show="openTab === 3">
                    <div class="ml-4 mt-5 text-lg">  
                        @foreach ($junta->convocatorias as $convocatoria)
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

                {{--COMISIONES--}}
                <div x-show="openTab === 4">
                    <div class="ml-4 mt-5 text-lg"> 
                        <form action="{{ route('infoComision') }}" method="GET">                         
                            @foreach ($junta->comisiones as $comision)
                            <div class="ml-4 mt-1">
                                <button type="submit" name="comision" value="{{$comision->id}}" class="flex gap-2 rounded-md hover:text-white hover:bg-gray-700 px-2">    
                                    <span class="material-icons-round scale-75">
                                        send
                                    </span>
                                    {{$comision->nombre}}
                                </button>  
                            </div>   
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-32">
            Actualmente no se encuentra información disponible sobre el centro seleccionado
        </div>
    @endif
</div>

@endsection

@section('contentBotttom')

@endsection

@vite(['resources/js/publico/info.js'])