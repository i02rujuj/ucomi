<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RepresentacionGobierno;
use Illuminate\Support\Facades\Validator;

class MiembrosGobiernoController extends Controller
{

    public function index(Request $request)
    {
        try {

            $miembrosGobierno = MiembroGobierno::select('id', 'idCentro', 'idUsuario', 'idJunta', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese');

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $miembrosGobierno = $miembrosGobierno->filters($request);
                    break;
                default:
                    $miembrosGobierno = $miembrosGobierno->where('estado', 1);
                    break;
            }

            $user = Auth::user();

            if($user->hasRole('admin')){
                $centros = Centro::select('id', 'nombre')->where('estado', 1)->get();

                $miembrosGobierno = MiembroGobierno::
                orderBy('fechaCese')
                ->orderBy('idCentro')
                ->orderBy('idRepresentacion')
                ->orderBy('idUsuario')
                ->get();
                }
            
            if($user->hasRole('responsable_centro')){
                $centro = MiembroGobierno::where('miembros_gobierno.idUsuario', $user->id)
                ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->join('centros', 'miembros_gobierno.idCentro', '=', 'centros.id')
                ->where('centros.estado', 1)
                ->select('centros.id', 'centros.nombre')
                ->first();

                $miembrosGobierno = MiembroGobierno::where('idCentro', $centro->id)
                ->orderBy('fechaCese')
                ->orderBy('idCentro')
                ->orderBy('idRepresentacion')
                ->orderBy('idUsuario')
                ->get();

                $centros=array($centro);
            }

            $users = User::select('id', 'name')->where('estado', 1)->get();
            $representacionesGobierno = RepresentacionGobierno::select('id', 'nombre')->where('estado', 1)->get();    
            
            if($request->input('action')=='limpiar'){
                return redirect()->route('miembrosGobierno')->with([
                    'centros' => $centros, 
                    'users' => $users, 
                    'representacionesGobierno' => $representacionesGobierno, 
                    'miembrosGobierno' => $miembrosGobierno,
                ]);
            }

            return view('miembrosGobierno', [
                'centros' => $centros, 
                'users' => $users, 
                'representacionesGobierno' => $representacionesGobierno, 
                'miembrosGobierno' => $miembrosGobierno,
                'filtroCentro' => $request['filtroCentro'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);
        
        } catch (\Throwable $th) {
            return redirect()->route('miembrosGobierno')->with('errors', 'No se pudieron obtener los miembros de centro: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idCentro' => 'required|integer|exists:App\Models\Centro,id',
                'idUsuario' => 'required|integer|exists:App\Models\User,id',
                'fechaTomaPosesion' => 'required|date',
                'fechaCese' => 'nullable|date',
                'idRepresentacion' => 'required|integer|exists:App\Models\RepresentacionGobierno,id',
            ], [
                // Mensajes error idCentro
                'idCentro.required' => 'El centro es obligatorio.',
                'idCentro.integer' => 'El centro debe ser un entero.',
                'idCentro.exists' => 'El centro seleccionado no existe.',
                // Mensajes error idUsuario
                'idUsuario.required' => 'El usuario es obligatorio.',
                'idUsuario.integer' => 'El usuario debe ser un entero.',
                'idUsuario.exists' => 'El usuario seleccionado no existe.',
                // Mensajes error fechaTomaPosesión
                'fechaTomaPosesion.required' => 'La fecha de toma de posesión es obligatoria.',
                'fechaTomaPosesion.date' => 'La fecha de toma de posesión debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error fechaCese
                'fechaCese.date' => 'La fecha de cese debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error idRepresentacion
                'idRepresentacion.required' => 'La representación es obligatoria.',
                'idRepresentacion.integer' => 'La representación debe ser un entero.',
                'idRepresentacion.exists' => 'La representación seleccionada no existe.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if($request->fechaCese==null){

                /// Comprobación existencia director actual en el centro
                if($request->idRepresentacion==config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR')){
                    $director = MiembroGobierno::select('id')
                        ->where('idCentro', $request->get('idCentro'))
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'))
                        ->where('fechaCese', null)
                        ->first();
    
                    if($director)
                        return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ya existe un Director/a | Decano/a vigente en el centro seleccionado')->withInput();
                }
    
                // Comprobación existencia secretario actual en el centro
                if($request->idRepresentacion==config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO')){
                    $secretario = MiembroGobierno::select('id')
                        ->where('idCentro', $request->get('idCentro'))
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO'))
                        ->where('fechaCese', null)
                        ->first();
    
                    if($secretario)
                        return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ya existe un Secretario/a vigente en el centro seleccionado')->withInput();
                }

                // Comprobación existencia usuario en el centro
                    $usuarioEnCentro = MiembroGobierno::select('id')
                        ->where('idCentro', $request->get('idCentro'))
                        ->where('idUsuario', $request->get('idUsuario'))
                        ->where('fechaCese', null)
                        ->first();
    
                    if($usuarioEnCentro)
                        return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ya existe el usuario vigente en el centro seleccionado')->withInput();
            }
            else{
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateTomaPosesion = new DateTime($request->fechaTomaPosesion);
                $dateCese = new DateTime($request->fechaCese);

                if ($dateTomaPosesion>$dateCese) {
                    return redirect()->route('miembrosGobierno')->with('error', 'La fecha de cese no puede ser anterior a la toma de posesión')->withInput();
                }  
            }

            $miembroGobierno = MiembroGobierno::create([
                "idCentro" => $request->idCentro,
                "idUsuario" => $request->idUsuario,
                "fechaTomaPosesion" => $request->fechaTomaPosesion,
                "fechaCese" => $request->fechaCese,
                "idRepresentacion" => $request->idRepresentacion,
            ]);

            return redirect()->route('miembrosGobierno')->with('success', 'Miembro del Equipo de Gobierno creado correctamente.');

        } catch (\Throwable $th) {
            return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ' . $th->getMessage());
        }
    }

    public function getDirectivos(Request $request)
    {
        try {
            // Falta filtrar entre fechas y estado 
            $director = MiembroGobierno::
                join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->where('miembros_gobierno.idCentro', $request->get('idCentro'))
                ->where('miembros_gobierno.fechaCese', null)
                ->whereIn('miembros_gobierno.idRepresentacion', [config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR')])
                ->select('users.id', 'users.name')
                ->first();

            $secretario = MiembroGobierno::
                join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->where('miembros_gobierno.idCentro', $request->get('idCentro'))
                ->where('miembros_gobierno.fechaCese', null)
                ->whereIn('miembros_gobierno.idRepresentacion', [config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO')])
                ->select('users.id', 'users.name')
                ->first();

            return response()->json(['director'=>$director, 'secretario'=>$secretario]);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se han encontrado directivos para el centro seleccionado.'], 404);
        }    
    }

    public function get(Request $request)
    {
        try {

            $miembro = MiembroGobierno::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de gobierno.'], 404);
            }
            
            return response()->json($miembro);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de gobierno.'], 404);
        }
    }

    public function delete(Request $request)
    {
        try {
            $miembro = MiembroGobierno::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Gobierno.'], 404);
            }

            $miembro->delete();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de Gobierno.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $miembro = MiembroGobierno::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Gobierno', 'status' => 404], 200);
            }

            if($request->data['fechaCese'] != null){
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateTomaPosesion = new DateTime($request->data['fechaTomaPosesion']);
                $dateCese = new DateTime($request->data['fechaCese']);

                if ($dateTomaPosesion>$dateCese) {
                    return response()->json(['error' => 'La fecha de cese no puede ser anterior a la toma de posesión', 'status' => 404]);
                }    
            }
            else{
                // Comprobación existencia usuario vigente en el centro
                $miembroRepetido = MiembroGobierno::select('id')
                ->where('idCentro', $miembro->idCentro)
                ->where('idUsuario', $miembro->idUsuario)
                ->where('fechaCese', null)
                ->count();

                if($miembroRepetido>1)
                    return response()->json(['error' => 'No se pudo editar el miembro del equipo de gobierno: ya existe el usuario vigente en el centro seleccionado', 'status' => 404]);
            }

            if($request->data['responsable'] == 0){
                $miembro->usuario->removeRole('responsable_centro');
            }
            else{
                $miembro->usuario->assignRole('responsable_centro');
            }

            //$miembro->idJunta = $request->data['idJunta'];
            $miembro->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembro->fechaCese = $request->data['fechaCese'];  
            $miembro->save();
            return response()->json(['message' => 'El miembro de Gobierno se ha actualizado correctamente.', 'status' => 200]);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar el miembro de gobierno. '.$th->getMessage(), 'status' => 404]);
        }
    }

    public function getByCentro(Request $request)
    {
        
        try {
            $miembros = MiembroGobierno::
                join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->join('representaciones_gobierno', 'miembros_gobierno.idRepresentacion', '=', 'representaciones_gobierno.id')
                ->where('miembros_gobierno.idCentro', $request->get('id'))
                ->where('miembros_gobierno.fechaCese', null)
                ->select('users.id', 'users.name', 'users.email', 'miembros_gobierno.idRepresentacion', 'representaciones_gobierno.nombre')
                ->get();

            return response()->json(['miembros'=>$miembros]);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se han encontrado miembros de gobierno para el centro seleccionado.'], 404);
        }    
    }

}
