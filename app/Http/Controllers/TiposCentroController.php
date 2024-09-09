<?php

namespace App\Http\Controllers;

use App\Models\TipoCentro;
use Illuminate\Http\Request;
/**
 * @brief Clase que contiene la lógica de negocio para la gestión de los tipos de centro
 * 
 * @author Javier Ruiz Jurado
 */
class TiposCentroController extends Controller
{
    /**
     * @brief Método principal encargado de obtener todos los tipos de centro
     * @return json Datos de los tipos de centro a obtener
     * @throws \Throwable Si no se pudieron obtener los tipos de centro
     */
    public function index()
    {
        try {
            $tiposCentro = TipoCentro::select('id', 'nombre')->get();

            if (!$tiposCentro) {
                return response()->json(['errors' => 'No se han podido obtener los tipos de centro.', 'status' => 422], 200);
            }

            return response()->json($tiposCentro);

        } catch (\Throwable $th) {
            return redirect()->route('home')->with(['error', 'No se han podido obtener los tipos de centro.']);
        }
    }
}
