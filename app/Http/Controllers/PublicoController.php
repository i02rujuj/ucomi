<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicoController extends Controller
{
    
    public function index()
    {
        $centros = Centro::where('estado', 1)->get();
        return view('publico.welcome',['centros' => $centros]);
    }
    
    public function info(Request $request)
    {
        $idCentro = $request->get('idCentro');
        $centros = Centro::where('estado', 1)->get();

        return view('publico.infoPublica',['idCentro' => $idCentro, 'centros' => $centros]);
    }
    
    public function login(Request $request)
    {

        if(Auth::check())
            return redirect()->route('home');
        else{
            $centros = Centro::where('estado', 1)->get();
            return view('publico.login',['centros' => $centros]);
        }
    }
}
