<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\DB;
use App\Models\RepresentacionGobierno;
use Illuminate\Support\Facades\Validator;

class MiembrosGobiernoController extends Controller
{
    public function index()
    {
        try {
            $centros = Centro::select('id', 'nombre')->where('estado', 1)->get();
            $users = User::select('id', 'name')->where('estado', 1)->get();
            $representacionesGobierno = RepresentacionGobierno::select('id', 'nombre')->where('estado', 1)->get();


            $miembrosGobierno = MiembroGobierno::orderBy('idCentro')->orderBy('idRepresentacion')->orderBy('idUsuario')->get();


            return view('miembrosGobierno', ['centros' => $centros, 'users' => $users, 'representacionesGobierno' => $representacionesGobierno, 'miembrosGobierno' => $miembrosGobierno]);
        } catch (\Throwable $th) {
            return redirect()->route('miembrosGobierno')->with('error', 'No se pudieron obtener los centros/usuarios | Miembro de Gobierno: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idCentro' => 'required|integer|exists:App\Models\Centro,id',
                'idUsuario' => 'required|integer|exists:App\Models\User,id',
                'fechaTomaPosesion' => 'required|date',
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
                // Mensajes error fechaInicio
                'fechaTomaPosesion.required' => 'La fecha de inicio es obligatoria.',
                'fechaTomaPosesion.date' => 'La fecha de inicio debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error idRepresentacion
                'idRepresentacion.required' => 'La representación es obligatoria.',
                'idRepresentacion.integer' => 'La representación debe ser un entero.',
                'idRepresentacion.exists' => 'La representación seleccionada no existe.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            /// Comprobación existencia director actual en el centro
            if($request->idRepresentacion==1){
                $director = MiembroGobierno::select('id')
                    ->where('idCentro', $request->get('idCentro'))
                    ->where('idRepresentacion', 1)
                    ->where('fechaCese', null)
                    ->where('estado', 1)
                    ->first();

                if($director)
                    return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ya existe un Director/a | Decano/a en activo en el centro seleccionado')->withInput();
            }

            // Comprobación existencia secretario actual en el centro
            if($request->idRepresentacion==2){
                $secretario = MiembroGobierno::select('id')
                    ->where('idCentro', $request->get('idCentro'))
                    ->where('idRepresentacion', 2)
                    ->where('fechaCese', null)
                    ->where('estado', 1)
                    ->first();

                if($secretario)
                    return redirect()->route('miembrosGobierno')->with('error', 'No se pudo crear el miembro del equipo de gobierno: ya existe un Secretario/a en activo en el centro seleccionado')->withInput();
            }

            $miembroGobierno = MiembroGobierno::create([
                "idCentro" => $request->idCentro,
                "idUsuario" => $request->idUsuario,
                "fechaTomaPosesion" => $request->fechaTomaPosesion,
                "idRepresentacion" => $request->idRepresentacion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
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
            $director = DB::table('miembros_gobierno')
                ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->where('miembros_gobierno.idCentro', $request->get('idCentro'))
                ->where('miembros_gobierno.estado', 1)
                ->whereIn('miembros_gobierno.idRepresentacion', [1])
                ->select('users.id', 'users.name')
                ->first();

            $secretario = DB::table('miembros_gobierno')
                ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->where('miembros_gobierno.idCentro', $request->get('idCentro'))
                ->where('miembros_gobierno.estado', 1)
                ->whereIn('miembros_gobierno.idRepresentacion', [2])
                ->select('users.id', 'users.name')
                ->first();

            return response()->json(['director'=>$director, 'secretario'=>$secretario]);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se han encontrado directivos para el centro seleccionado.'], 404);
        }    
    }
}
