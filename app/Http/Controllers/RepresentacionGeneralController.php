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
                return response()->json(['error' => 'No se ha encontrado la representación.'], 404);
            }
            return response()->json($rep);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado la representación.'], 404);
        }
    }

    public function all()
    {
        try {
            $representaciones = RepresentacionGeneral::all()->where('estado',1);

            if (!$representaciones) {
                return response()->json(['error' => 'No se han podido obtener las representaciones.'], 404);
            }

            return response()->json($representaciones);
        } catch (\Throwable $th) {
            return redirect()->route('representaciones')->with('error', 'No se pudieron obtener las representaciones: ' . $th->getMessage());
        }
    }
}
