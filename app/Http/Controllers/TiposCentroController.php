<?php

namespace App\Http\Controllers;

use App\Models\TipoCentro;
use Illuminate\Http\Request;

class TiposCentroController extends Controller
{
    public function index()
    {
        try {
            $tiposCentro = TipoCentro::select('id', 'nombre', 'estado')->where('estado', 1)->get();

            if (!$tiposCentro) {
                return response()->json(['error' => 'No se han encontrado tipos de centro.'], 404);
            }

            return response()->json($tiposCentro);

        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudieron obtener los tipos de centros: ' . $th->getMessage());
        }
    }
}
