<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JuntasController;
use App\Http\Controllers\CentrosController;
use App\Http\Controllers\PublicoController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\TiposCentroController;
use App\Http\Controllers\MiembrosJuntaController;
use App\Http\Controllers\RepresentacionController;
use App\Http\Controllers\MiembrosComisionController;
use App\Http\Controllers\MiembrosGobiernoController;
use App\Http\Controllers\ConvocatoriasJuntaController;
use App\Http\Controllers\ConvocatoriasComisionController;

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

// Modificamos las rutas por defecto(vendor/laravel/ui/src/AuthRouteMethods.php) para que al entrar a login o register envíe a welcome
Route::get('register', function () {
    return redirect()->route('welcome');
})->name('register');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});

// PÚBLICO
Route::get('/', [PublicoController::class, 'index'])->name('welcome');
Route::get('/info_junta', [PublicoController::class, 'infoJunta'])->name('infoJunta');
Route::get('/info_comision', [PublicoController::class, 'infoComision'])->name('infoComision');
Route::get('login', [PublicoController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    
    // USERS
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/user/get', [UserController::class, 'get']);
    Route::post('/user/all', [UserController::class, 'all']);
    Route::get('/perfil', function () { return view('perfil');})->name('perfil');
    Route::post('/perfil', [UserController::class, 'store'])->name('perfil.store');
    Route::post('/save_image_perfil', [UserController::class, 'saveImagePerfil'])->name('saveImagePerfil');
    Route::get('/certificados', [UserController::class, 'index'])->name('certificados');
    Route::post('/generar_certificado', [UserController::class, 'generarCertificado'])->name('generarCertificado');
    Route::post('/generar_certificado_asistencia', [UserController::class, 'generarCertificadoAsistencia'])->name('generarCertificadoAsistencia');
});

Route::group(['middleware' => ['responsable:admin|centro']], function () {

    // CENTROS
    Route::get('/centros', [CentrosController::class, 'index'])->name('centros');
    Route::post('/centro/add', [CentrosController::class, 'store'])->middleware('responsable:admin');
    Route::post('/centro/delete', [CentrosController::class, 'delete'])->middleware('responsable:admin');
    Route::post('/centro/get', [CentrosController::class, 'get']);
    Route::post('/centro/update', [CentrosController::class, 'update']);
    Route::post('/centro/validate', [CentrosController::class, 'validateCentro']);

    // TIPOS DE CENTROS
    Route::post('/tiposCentro', [TiposCentroController::class, 'index']);

    // MIEMBROS EQUIPO DE GOBIERNO
    Route::get('/miembros_gobierno', [MiembrosGobiernoController::class, 'index'])->name('miembrosGobierno');
    Route::post('/miembro_gobierno/add', [MiembrosGobiernoController::class, 'store'])->name('store');
    Route::post('/miembro_gobierno/delete', [MiembrosGobiernoController::class, 'delete']);
    Route::post('/miembro_gobierno/get', [MiembrosGobiernoController::class, 'get']);
    Route::post('/miembro_gobierno/update', [MiembrosGobiernoController::class, 'update']);
    Route::post('/miembro_gobierno/getDirectivos', [MiembrosGobiernoController::class, 'getDirectivos']);
    Route::post('/miembro_gobierno/validate', [MiembrosGobiernoController::class, 'validateMiembro']);
}); 

Route::group(['middleware' => ['responsable:admin|centro|junta']], function () {

    // JUNTAS
    Route::get('/juntas', [JuntasController::class, 'index'])->name('juntas');
    Route::post('/junta/add', [JuntasController::class, 'store'])->name('store')->middleware('responsable:admin|centro');
    Route::post('/junta/delete', [JuntasController::class, 'delete'])->middleware('responsable:admin|centro');
    Route::post('/junta/get', [JuntasController::class, 'get']);
    Route::post('/junta/update', [JuntasController::class, 'update']);
    Route::post('/junta/all', [JuntasController::class, 'all'])->middleware('responsable:admin|centro');
    Route::post('/junta/validate', [JuntasController::class, 'validateJunta']);
    Route::post('/junta/miembros', [JuntasController::class, 'miembros']);

    // CONVOCATORIAS JUNTA
    Route::post('/convocatoria_junta/add', [ConvocatoriasJuntaController::class, 'store'])->name('store')->middleware('responsable:admin|centro|junta');
    Route::post('/convocatoria_junta/delete', [ConvocatoriasJuntaController::class, 'delete'])->middleware('responsable:admin|centro|junta');
    Route::post('/convocatoria_junta/get', [ConvocatoriasJuntaController::class, 'get'])->middleware('responsable:admin|centro|junta');
    Route::post('/convocatoria_junta/update', [ConvocatoriasJuntaController::class, 'update'])->middleware('responsable:admin|centro|junta');
    Route::post('/convocatoria_junta/validate', [ConvocatoriasJuntaController::class, 'validateConvocatoria'])->middleware('responsable:admin|centro|junta');
    Route::post('/convocatoria_junta/convocar', [ConvocatoriasJuntaController::class, 'convocados'])->middleware('responsable:admin|centro|junta');

    // MIEMBROS JUNTA
    Route::get('/miembros_junta', [MiembrosJuntaController::class, 'index'])->name('miembrosJunta');
    Route::post('/miembro_junta/add', [MiembrosJuntaController::class, 'store'])->name('store');
    Route::post('/miembro_junta/delete', [MiembrosJuntaController::class, 'delete']);
    Route::post('/miembro_junta/get', [MiembrosJuntaController::class, 'get']);
    Route::post('/miembro_junta/update', [MiembrosJuntaController::class, 'update']);
    Route::post('/miembro_junta/validate', [MiembrosJuntaController::class, 'validateMiembro']);
});

Route::group(['middleware' => ['miembro:admin|centro|junta']], function () {
    // CONVOCATORIAS JUNTA
    Route::get('/convocatorias_junta', [ConvocatoriasJuntaController::class, 'index'])->name('convocatoriasJunta');
    Route::post('/convocatoria_junta/asistir', [ConvocatoriasJuntaController::class, 'asistir']);
});

Route::group(['middleware' => ['miembro:admin|centro|junta|comision']], function () {
 // CONVOCATORIAS COMISION
 Route::get('/convocatorias_comision', [ConvocatoriasComisionController::class, 'index'])->name('convocatoriasComision');
 Route::post('/convocatoria_comision/asistir', [ConvocatoriasComisionController::class, 'asistir']);
});

Route::group(['middleware' => ['responsable:admin|centro|junta|comision']], function () {

    // COMISIONES
    Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones');
    Route::post('/comision/add', [ComisionController::class, 'store'])->name('store')->middleware('responsable:admin|centro|junta');
    Route::post('/comision/delete', [ComisionController::class, 'delete'])->middleware('responsable:admin|centro|junta');
    Route::post('/comision/get', [ComisionController::class, 'get']);
    Route::post('/comision/update', [ComisionController::class, 'update']);
    Route::post('/comision/validate', [ComisionController::class, 'validateComision']);

    // MIEMBROS COMISIÓN
    Route::get('/miembros_comision', [MiembrosComisionController::class, 'index'])->name('miembrosComision');
    Route::post('/miembro_comision/add', [MiembrosComisionController::class, 'store'])->name('store');
    Route::post('/miembro_comision/delete', [MiembrosComisionController::class, 'delete']);
    Route::post('/miembro_comision/get', [MiembrosComisionController::class, 'get']);
    Route::post('/miembro_comision/update', [MiembrosComisionController::class, 'update']);
    Route::post('/miembro_comision/validate', [MiembrosComisionController::class, 'validateMiembro']);

    // CONVOCATORIAS COMISION
    Route::post('/convocatoria_comision/add', [ConvocatoriasComisionController::class, 'store'])->name('store');
    Route::post('/convocatoria_comision/delete', [ConvocatoriasComisionController::class, 'delete']);
    Route::post('/convocatoria_comision/get', [ConvocatoriasComisionController::class, 'get']);
    Route::post('/convocatoria_comision/update', [ConvocatoriasComisionController::class, 'update']);
    Route::post('/convocatoria_comision/validate', [ConvocatoriasComisionController::class, 'validateConvocatoria']);
    Route::post('/convocatoria_comision/convocar', [ConvocatoriasComisionController::class, 'convocados']);

    // REPRESENTACIONES
    Route::post('/representacion/get', [RepresentacionController::class, 'get']);
    Route::post('/representacion/all', [RepresentacionController::class, 'all']);
});

