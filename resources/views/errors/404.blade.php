@extends('layouts.app')

@section ('title')
    {{ config('app.name', 'Ucomi') }} 
@endsection

@section('contentTop')
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>4<span>0</span>4</h1>
            </div>
            <p>La página a la que estás intentando acceder no está disponible.
            </p>
            <a href="/">Página de inicio</a>
        </div>
    </div>
@endsection
