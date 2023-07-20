<?php

namespace App\Http\Controllers;

use App\Models\RepresentacionGobierno;
use Illuminate\Http\Request;

class RepresentacionController extends Controller
{
    public function get(Request $request)
    {
        try {
            $rep = RepresentacionGobierno::where('id', $request->id)->first();
            if (!$rep) {
                return response()->json(['error' => 'No se ha encontrado la representación del gobierno.'], 404);
            }
            return response()->json($rep);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado la representación del gobierno.'], 404);
        }
    }
}
