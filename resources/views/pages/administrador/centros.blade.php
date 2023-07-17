@extends ('layouts.admin')
@section ('title')
Sedes
@endsection

@section ('content')
<div>
    @include('components.administrador.Dashboard')

    <div class="md:ml-64 lg:ml-64 mt-14">
        <div class="mx-auto p-6">

            @include('components.shared.Alertas')
            @include('components.administrador.sedes.FormSedes')

            <hr className="my-6 border-t border-gray-300" />

            @include('components.shared.Filtro')
            @include('components.shared.Enabled')
            @include('components.administrador.sedes.TablaSedes')

        </div>
    </div>
</div>

<!-- Contenido Principal -->
@endsection

@vite(['resources/js/admin/sedes.js'])