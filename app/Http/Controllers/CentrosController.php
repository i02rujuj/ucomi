<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use Illuminate\Http\Request;

class CentrosController extends Controller
{
    public function index()
    {
        try {
            $centros = Centro::select('id', 'nombre', 'direccion', 'tipo')->get();
            return view('centros', ['centros' => $centros]);
        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudieron obtener los centros: ' . $th->getMessage());
        }
    }
}
