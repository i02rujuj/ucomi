<?php

namespace App\Http\Controllers;

use App\Models\Representacion;
use Illuminate\Http\Request;

class RepresentacionController extends Controller
{
    public function get(Request $request)
    {
        try {
            $rep = Representacion::where('id', $request->id)->first();
            if (!$rep) {
                return response()->json(['errors' => 'No se ha encontrado la representación.', 'status' => 422], 200);
            }
            return response()->json($rep);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la representación.', 'status' => 422], 200);
        }
    }

    public function all()
    {
        try {
            $representaciones = Representacion::all();
            if (!$representaciones) {
                return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 422], 200);
            }
            return response()->json($representaciones);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 422], 200);
        }
    }
}
