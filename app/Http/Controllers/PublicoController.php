<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Models\Centro;
use App\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flasher\Prime\Notification\NotificationInterface;

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
    
    public function infoJunta(Request $request)
    {
        if($request->get('centro')){
            $centro = Centro::
                where('id', $request->get('centro'))
                ->first();

                if($centro){
                    $juntaActual = null;

                    if($request->get('centro')!=null){
                        $juntaActual = Junta::
                            with('miembros')
                            ->where('idCentro', $request->get('centro'))
                            ->whereNull('fechaDisolucion')
                            ->orderBy('created_at')
                            ->first();
                    }
        
                    return view('publico.infoJunta',['junta' => $juntaActual, 'centro' => $centro]);
                }
        }   

        
        flash('No es posible consultar la información del centro.', NotificationInterface::ERROR);
        return redirect()->route('welcome');
    }

    public function infoComision(Request $request)
    {
        if($request->get('comision')){
            $comision = Comision::
                where('id', $request->get('comision'))
                ->first();

                if($comision){
                    
                    return view('publico.infoComision',['comision' => $comision]);
                }
        }   

        flash('No es posible consultar la información de la comisión.', NotificationInterface::ERROR);
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
