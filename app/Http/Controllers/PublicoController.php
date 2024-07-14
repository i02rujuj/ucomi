<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PublicoController extends Controller
{
    
    public function index()
    {
        $centros = Centro::select('id', 'nombre', 'idTipo', 'logo')
        ->orderBy('idTipo')
        ->orderBy('updated_at')
        ->get();
        return view('publico.welcome',['centros' => $centros]);
    }
    
    public function info(Request $request)
    {
        $idCentro = $request->get('idCentro');
        $centros = Centro::get();

        return view('publico.infoPublica',['idCentro' => $idCentro, 'centros' => $centros]);
    }
    
    public function login(Request $request)
    {
        if(Auth::check())
            return redirect()->route('home');
        else{
            return view('publico.login');
        }
    }
}
