<?php

namespace App\Http\Controllers;

use App\Models\Representacion;
use Illuminate\Http\Request;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de las representaciones de los miembros
 * 
 * @author Javier Ruiz Jurado
 */
class RepresentacionController extends Controller
{
    /**
     * @brief Método encargado de obtener una representación
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos de la representación a obtener
     * @throws \Throwable Si no se pudo obtener la representación, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $rep = Representacion::where('id', $request->id)->first();
            if (!$rep) {
                return response()->json(['errors' => 'No se ha encontrado la representación.', 'status' => 422], 200);
            }
            return response()->json($rep);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la representación.', 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener todas las representaciones
     * @return json Datos de las representaciones a obtener
     * @throws \Throwable Si no se pudieron obtener las representaciones
     */
    public function all()
    {
        try {
            $representaciones = Representacion::all();
            if (!$representaciones) {
                return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 422], 200);
            }
            return response()->json($representaciones);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 500], 200);
        }
    }
}
