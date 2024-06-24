@extends ('layouts.panel')
@section ('title')
Panel
@endsection

@section ('content')
<div class="md:ml-64 lg:ml-64 mt-14">
    <div class="mx-auto p-6">
        <img id="carousel-image" src="{{ asset('img/inicio1.png') }}" alt="Beneficio 1" class="rounded-lg w-full h-auto object-cover" />
    </div>
</div>
@endsection

@vite(['resources/js/panel.js'])
