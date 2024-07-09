<?php

namespace App\Http\Controllers;

use App\Models\RepresentacionGeneral;
use Illuminate\Http\Request;

class RepresentacionGeneralController extends Controller
{
    public function get(Request $request)
    {
        try {
            $rep = RepresentacionGeneral::where('id', $request->id)->first();
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
            $representaciones = RepresentacionGeneral::all();

            if (!$representaciones) {
                return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 422], 200);
            }

            return response()->json($representaciones);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener las representaciones.', 'status' => 422], 200);
        }
    }
}
