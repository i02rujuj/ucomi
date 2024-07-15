<?php

namespace App\Http\Controllers;

use App\Models\Junta;
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
        if($request->get('centro')){
            $centro = Centro::
                where('id', $request->get('centro'))
                ->first();

                if($centro){
                    $juntaActual = null;

                    if($request->get('centro')!=null){
                        $juntaActual = Junta::
                            with('miembrosJunta')
                            ->where('idCentro', $request->get('centro'))
                            ->whereNull('fechaDisolucion')
                            ->orderBy('created_at')
                            ->first();
                    }
        
                    return view('publico.infoPublica',['junta' => $juntaActual, 'centro' => $centro]);
                }
        }   
        
        return redirect()->route('welcome');
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
