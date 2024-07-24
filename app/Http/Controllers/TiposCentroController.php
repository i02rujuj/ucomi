<?php

namespace App\Http\Controllers;

use App\Models\TipoCentro;
use Illuminate\Http\Request;

class TiposCentroController extends Controller
{
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
