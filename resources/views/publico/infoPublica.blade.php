@extends('layouts.app')

@section ('title')
    Información Pública del centro 
@endsection

@section('contentTop')
    <span id="idCentro" class="hidden">{{$idCentro}}</span>
    <div id="equipoGobierno"></div>
@endsection

<script src="{{asset('js/orgchart.js')}}"></script>
@vite(['resources/js/publico/info.js'])