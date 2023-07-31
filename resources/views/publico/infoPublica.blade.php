@extends('layouts.app')

@section ('title')
    Información Pública del centro 
@endsection

@section('contentTop')
    <div id="equipoGobierno"></div>
@endsection

<script src="{{asset('js/orgchart.js')}}"></script>
@vite(['resources/js/publico/info.js'])