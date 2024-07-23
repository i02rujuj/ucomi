<?php

namespace App\Http\Controllers;

use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroComision;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $miembrosGobierno = MiembroGobierno::
        where('idUsuario', Auth::user()->id)
        ->whereNull('fechaCese')
        ->orderBy('responsable', 'desc')
        ->orderBy('fechaTomaPosesion', 'desc')
        ->orderBy('idRepresentacion')
        ->get(); 

        $miembrosJunta = MiembroJunta::
        where('idUsuario', Auth::user()->id)
        ->whereNull('fechaCese')
        ->orderBy('responsable', 'desc')
        ->orderBy('fechaTomaPosesion', 'desc')
        ->orderBy('idRepresentacion')
        ->get(); 

        $miembrosComision = MiembroComision::
        where('idUsuario', Auth::user()->id)
        ->whereNull('fechaCese')
        ->orderBy('responsable', 'desc')
        ->orderBy('fechaTomaPosesion', 'desc')
        ->orderBy('idRepresentacion')
        ->get(); 

        return view('home',['miembrosGobierno' => $miembrosGobierno, 'miembrosJunta' => $miembrosJunta, 'miembrosComision' => $miembrosComision]);
    }
}
