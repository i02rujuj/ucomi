<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CentrosController;

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

// CENTROS
Route::get('/centros', [CentrosController::class, 'index'])->name('centros');
Route::post('/centros', [CentrosController::class, 'store'])->name('centros.store');
Route::post('/centro/delete', [CentrosController::class, 'delete']);
Route::post('/centro/get', [CentrosController::class, 'get']);
Route::post('/centro/update', [CentrosController::class, 'update']);

    

