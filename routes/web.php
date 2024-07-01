<?php

use App\Models\Centro;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JuntasController;
use App\Http\Controllers\PerfilController;
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
use App\Http\Controllers\RepresentacionGeneralController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

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

Route::get('/', [PublicoController::class, 'index'])->name('welcome');
Route::get('/info', [PublicoController::class, 'info'])->name('infoPublica');
Route::get('login', [PublicoController::class, 'login'])->name('login');

Route::get('/home', [HomeController::class, 'index'])->name('home');

///////////////////////////////////////////////////////////////////////////////
// Modificamos las rutas por defecto(vendor/laravel/ui/src/AuthRouteMethods.php) para que al entrar a login o register envíe a welcome

Route::get('register', function () {
    return redirect()->route('welcome');
})->name('register');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});

///////////////////////////////////////////////////////////////////////////////

Route::get('/perfil', [UserController::class, 'index'])->name('perfil');
Route::post('/perfil', [UserController::class, 'store'])->name('perfil.store');
Route::post('/save_image_perfil', [UserController::class, 'saveImagePerfil'])->name('saveImagePerfil');
Route::get('/certificados', [UserController::class, 'certificados'])->name('certificados');
Route::post('/generar_certificado', [UserController::class, 'generarCertificado'])->name('generarCertificado');
Route::post('/centro/all', [CentrosController::class, 'all']);
Route::post('/miembros_gobierno/getbycentro', [MiembrosGobiernoController::class, 'getByCentro']);
Route::post('/miembros_junta/getbycentro', [MiembrosJuntaController::class, 'getByCentro']);
Route::post('/centro/get', [CentrosController::class, 'get']);

Route::group(['middleware' => ['role:admin']], function () {
    // CENTROS
    Route::get('/centros', [CentrosController::class, 'index'])->name('centros');
    Route::post('/centro/add', [CentrosController::class, 'store']);
    Route::post('/centro/delete', [CentrosController::class, 'delete']);
    Route::post('/centro/update', [CentrosController::class, 'update']);
    Route::post('/centro/validate', [CentrosController::class, 'validateCentro']);

    // TIPOS DE CENTROS
    Route::post('/tiposCentro', [TiposCentroController::class, 'index']);
});

Route::group(['middleware' => ['role:admin|responsable_centro']], function () {
    // JUNTAS
    Route::get('/juntas', [JuntasController::class, 'index'])->name('juntas');
    Route::post('/juntas', [JuntasController::class, 'store'])->name('juntas.store');
    Route::post('/junta/delete', [JuntasController::class, 'delete']);
    Route::post('/junta/get', [JuntasController::class, 'get']);
    Route::post('/junta/update', [JuntasController::class, 'update']);
    Route::post('/junta/all', [JuntasController::class, 'all']);

    // MIEMBROS EQUIPO DE GOBIERNO
    Route::get('/miembros_gobierno', [MiembrosGobiernoController::class, 'index'])->name('miembrosGobierno');
    Route::post('/miembros_gobierno', [MiembrosGobiernoController::class, 'store'])->name('miembrosGobierno.store');
    Route::post('/miembro_gobierno/delete', [MiembrosGobiernoController::class, 'delete']);
    Route::post('/miembro_gobierno/get', [MiembrosGobiernoController::class, 'get']);
    Route::post('/miembro_gobierno/update', [MiembrosGobiernoController::class, 'update']);
    Route::post('/miembro_gobierno/getDirectivos', [MiembrosGobiernoController::class, 'getDirectivos']);

    
}); 

Route::group(['middleware' => ['role:admin|responsable_centro|responsable_junta']], function () {
    // COMISIONES
    Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones');
    Route::post('/comisiones', [ComisionController::class, 'store'])->name('comisiones.store');
    Route::post('/comision/delete', [ComisionController::class, 'delete']);
    Route::post('/comision/get', [ComisionController::class, 'get']);
    Route::post('/comision/update', [ComisionController::class, 'update']);

    // CONVOCATORIAS JUNTA
    Route::get('/convocatorias_junta', [ConvocatoriasJuntaController::class, 'index'])->name('convocatoriasJunta');
    Route::post('/convocatorias_junta', [ConvocatoriasJuntaController::class, 'store'])->name('convocatoriasJunta.store');
    Route::post('/convocatorias_junta/delete', [ConvocatoriasJuntaController::class, 'delete']);
    Route::post('/convocatorias_junta/get', [ConvocatoriasJuntaController::class, 'get']);
    Route::post('/convocatorias_junta/update', [ConvocatoriasJuntaController::class, 'update']);

    // MIEMBROS JUNTA
    Route::get('/miembros_junta', [MiembrosJuntaController::class, 'index'])->name('miembrosJunta');
    Route::post('/miembros_junta', [MiembrosJuntaController::class, 'store'])->name('miembrosJunta.store');
    Route::post('/miembro_junta/delete', [MiembrosJuntaController::class, 'delete']);
    Route::post('/miembro_junta/get', [MiembrosJuntaController::class, 'get']);
    Route::post('/miembro_junta/update', [MiembrosJuntaController::class, 'update']);
});

Route::group(['middleware' => ['role:admin|responsable_centro|responsable_junta|responsable_comision']], function () {
    // MIEMBROS COMISIÓN
    Route::get('/miembros_comision', [MiembrosComisionController::class, 'index'])->name('miembrosComision');
    Route::post('/miembros_comision', [MiembrosComisionController::class, 'store'])->name('miembrosComision.store');
    Route::post('/miembro_comision/delete', [MiembrosComisionController::class, 'delete']);
    Route::post('/miembro_comision/get', [MiembrosComisionController::class, 'get']);
    Route::post('/miembro_comision/update', [MiembrosComisionController::class, 'update']);

    // CONVOCATORIAS COMISION
    Route::get('/convocatorias_comision', [ConvocatoriasComisionController::class, 'index'])->name('convocatoriasComision');
    Route::post('/convocatorias_comision', [ConvocatoriasComisionController::class, 'store'])->name('convocatoriasComision.store');
    Route::post('/convocatorias_comision/delete', [ConvocatoriasComisionController::class, 'delete']);
    Route::post('/convocatorias_comision/get', [ConvocatoriasComisionController::class, 'get']);
    Route::post('/convocatorias_comision/update', [ConvocatoriasComisionController::class, 'update']);

    // REPRESENTACIONES GENERAL
    Route::post('/representacion_general/get', [RepresentacionGeneralController::class, 'get']);
    Route::post('/representacion_general/all', [RepresentacionGeneralController::class, 'all']);

    // USERS
    Route::post('/user/get', [UserController::class, 'get']);
    });

