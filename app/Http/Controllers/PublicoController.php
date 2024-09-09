<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Models\Centro;
use App\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de la parte pública de la aplicación
 * 
 * @author Javier Ruiz Jurado
 */
class PublicoController extends Controller
{
    /**
     * @brief Método principal devuelve la vista principal de la aplicación con los centros
     * @return view welcome.blade.php con los centros de la base de datos
     */
    public function index()
    {
        $centros = Centro::select('id', 'nombre', 'idTipo', 'logo')
        ->orderBy('idTipo')
        ->get();

        return view('publico.welcome',['centros' => $centros]);
    }
    
    /**
     * @brief Método que devuelve la vista de información de la junta dependiento del centro seleccionado por el usuario
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view infoJunta.blade.php con la información de la junta actual del centro seleccionado
     */
    public function infoJunta(Request $request)
    {
        if($request->get('centro')){
            $centro = Centro::
                where('id', $request->get('centro'))
                ->first();

                if($centro){
                    $juntaActual = null;

                    if($request->get('centro')!=null){
                        $juntaActual = Junta::
                            with('miembros')
                            ->where('idCentro', $request->get('centro'))
                            ->whereNull('fechaDisolucion')
                            ->orderBy('created_at')
                            ->first();
                    }
        
                    return view('publico.infoJunta',['junta' => $juntaActual, 'centro' => $centro]);
                }
        }   

        return redirect()->route('welcome')->with(['errors' => 'No es posible consultar la información del centro.']);
    }

    /**
     * @brief Método que devuelve la vista de información de la comisión dependiento de la comisión seleccionada por el usuario
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view infoComision.blade.php con la información de la comisión actual seleccionada
     */
    public function infoComision(Request $request)
    {
        if($request->get('comision')){
            $comision = Comision::
                where('id', $request->get('comision'))
                ->first();

                if($comision){
                    return view('publico.infoComision',['comision' => $comision]);
                }
        }   

        return redirect()->route('welcome')->with(['errors' => 'No es posible consultar la información de la comisión.']);
    }
    
    /**
     * @brief Método que devuelve la vista de login si el usuario no se encuentra autenticado o devuelve la vista de home en caso contrario
     * @return view login.blade.php o home.blade.php
     */
    public function login()
    {
        if(Auth::check())
            return redirect()->route('home');
        else{
            return view('publico.login');
        }
    }
}
