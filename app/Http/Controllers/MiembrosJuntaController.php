<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Junta;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\RepresentacionGeneral;
use Illuminate\Support\Facades\Validator;

class MiembrosJuntaController extends Controller
{
    public function index()
    {
        try {
            $juntas = Junta::where('estado', 1)->get();
            $users = User::select('id', 'name')->where('estado', 1)->get();
            $representacionesGeneral = RepresentacionGeneral::select('id', 'nombre')->where('estado', 1)->get();

            $miembrosJunta = MiembroJunta::orderBy('idJunta')->orderBy('idRepresentacion')->orderBy('estado')->orderBy('idUsuario')->get();

            return view('miembrosJunta', ['juntas' => $juntas, 'users' => $users, 'representacionesGeneral' => $representacionesGeneral, 'miembrosJunta' => $miembrosJunta]);
        } catch (\Throwable $th) {
            return redirect()->route('miembrosJunta')->with('error', 'No se pudieron obtener algunos datos referentes a los miembros de Junta: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idJunta' => 'required|integer|exists:App\Models\Junta,id',
                'idUsuario' => 'required|integer|exists:App\Models\User,id',
                'fechaTomaPosesion' => 'required|date',
                'fechaCese' => 'nullable|date',
                'idRepresentacion' => 'required|integer|exists:App\Models\RepresentacionGeneral,id',
            ], [
                // Mensajes error idJunta
                'idJunta.required' => 'La junta es obligatoria.',
                'idJunta.integer' => 'La junta debe ser un entero.',
                'idJunta.exists' => 'La junta seleccionada no existe.',
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

            // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
            $dateTomaPosesion = new DateTime($request->fechaTomaPosesion);
            $dateCese = new DateTime($request->fechaCese);

            if ($dateTomaPosesion>$dateCese) {
                return redirect()->route('miembrosJunta')->with('error', 'La fecha de cese no puede ser anterior a la toma de posesión')->withInput();
            }  

            // Comprobación existencia miembro repetido en la misma junta
            $miembroRepetido = MiembroJunta::select('id')
                        ->where('idJunta', $request->get('idJunta'))
                        ->where('idUsuario', $request->get('idUsuario'))
                        ->where('fechaCese', null)
                        ->where('estado', 1)
                        ->first();
    
            if($miembroRepetido)
                return redirect()->route('miembrosJunta')->with('error', 'No se pudo crear el miembro de Junta: ya existe un miembro en activo para la junta seleccionada')->withInput();
                

            $miembroJunta = MiembroJunta::create([
                "idJunta" => $request->idJunta,
                "idUsuario" => $request->idUsuario,
                "fechaTomaPosesion" => $request->fechaTomaPosesion,
                "fechaCese" => $request->fechaCese,
                "idRepresentacion" => $request->idRepresentacion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('miembrosJunta')->with('success', 'Miembro de Junta creado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('miembrosJunta')->with('error', 'No se pudo crear el miembro de junta: ' . $th->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $miembro = MiembroJunta::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de junta.'], 404);
            }
            return response()->json($miembro);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de junta.'], 404);
        }
    }

    public function delete(Request $request)
    {
        try {
            $miembro = MiembroJunta::where('id', $request->id)->first();

            if ($request->estado == 0) {
                $miembro->estado = 1;
            } else {
                $miembro->estado = 0;
            }

            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Junta.'], 404);
            }

            $miembro->save();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de Junta.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $miembro = MiembroJunta::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Junta', 'status' => 404], 200);
            }

            // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
            $dateTomaPosesion = new DateTime($request->data['fechaTomaPosesion']);
            $dateCese = new DateTime($request->data['fechaCese']);

            if ($dateTomaPosesion>$dateCese) {
                return response()->json(['error' => 'La fecha de cese no puede ser anterior a la toma de posesión', 'status' => 404], 200);
            }          

            $miembro->idRepresentacion = $request->data['idRepresentacion'];
            $miembro->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembro->fechaCese = $request->data['fechaCese'];  
            $miembro->save();
            return response()->json(['message' => 'El miembro de Junta se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar el miembro de junta.', 'status' => 404], 404);
        }
    }
}
