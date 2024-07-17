@extends ('layouts.panel')
@section ('title')
Panel
@endsection

@section ('content')
<div class="md:ml-64 lg:ml-64 mt-14">

    <div>
        @if (session()->has('success'))
            <div class="mb-2 py-1 px-4 text-sm font-medium bg-green-100 text-slate-700 rounded" role="alert">
                {{ session("success") }}
            </div>
        @endif
            
        @if (session()->has('errors'))
            <div class="errorMessage my-2 py-1 px-4 text-sm font-medium bg-red-100 text-slate-700 rounded" role="alert">
                {{ session("errors") }}
            </div>
        @endif
    </div>

    <div class="mx-auto p-6">
        <img id="carousel-image" src="{{ asset('img/inicio1.png') }}" alt="Beneficio 1" class="rounded-lg w-full h-auto object-cover" />
    </div>
</div>

@endsection
