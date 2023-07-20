<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JuntasController;
use App\Http\Controllers\CentrosController;
use App\Http\Controllers\TiposCentroController;
use App\Http\Controllers\MiembrosGobiernoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', function () {
    if(Auth::user())
        return redirect()->route('home');
    else
        return view('welcome');
})->name('welcome');;

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Modificamos las rutas por defecto(vendor/laravel/ui/src/AuthRouteMethods.php) para que al entrar a login o register envíe a welcome
Route::get('login', function () {
    return redirect()->route('welcome');
})->name('login');

Route::get('register', function () {
    return redirect()->route('welcome');
})->name('register');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


// TIPOS DE CENTROS
Route::post('/tiposCentro', [TiposCentroController::class, 'index']);

// CENTROS
Route::get('/centros', [CentrosController::class, 'index'])->name('centros');
Route::post('/centros', [CentrosController::class, 'store'])->name('centros.store');
Route::post('/centro/delete', [CentrosController::class, 'delete']);
Route::post('/centro/get', [CentrosController::class, 'get']);
Route::post('/centro/update', [CentrosController::class, 'update']);
Route::post('/centro/all', [CentrosController::class, 'all']);

// MIEMBROS EQUIPO DE GOBIERNO
Route::get('/miembrosGobierno', [MiembrosGobiernoController::class, 'index'])->name('miembrosGobierno');
Route::post('/miembrosGobierno', [MiembrosGobiernoController::class, 'store'])->name('miembrosGobierno.store');
Route::post('/miembro_gobierno/delete', [MiembrosGobiernoController::class, 'delete']);
Route::post('/miembro_gobierno/get', [MiembrosGobiernoController::class, 'get']);
Route::post('/miembro_gobierno/update', [MiembrosGobiernoController::class, 'update']);
Route::post('/miembro_gobierno/getDirectivos', [MiembrosGobiernoController::class, 'getDirectivos']);

// JUNTAS
Route::get('/juntas', [JuntasController::class, 'index'])->name('juntas');
Route::post('/juntas', [JuntasController::class, 'store'])->name('juntas.store');
Route::post('/junta/delete', [JuntasController::class, 'delete']);
Route::post('/junta/get', [JuntasController::class, 'get']);
Route::post('/junta/update', [JuntasController::class, 'update']);

    

