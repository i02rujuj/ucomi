<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Junta;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JuntasController extends Controller
{
    public function index()
    {
        try {
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion', 'estado')->orderBy('idCentro')->orderBy('fechaConstitucion')->get();
            $centros = Centro::select('id', 'nombre')->where('estado', 1)->get();
            return view('juntas', ['juntas' => $juntas, 'centros' => $centros,]);
        } catch (\Throwable $th) {
            return redirect()->route('juntas')->with('error', 'No se pudieron obtener las juntas: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idCentro' => 'required|integer|exists:App\Models\Centro,id',
                'fechaConstitucion' => 'required|date',
                'fechaDisolucion' => 'nullable|date',
                'idDirector' => 'required|integer|exists:App\Models\MiembroGobierno,id',
                'idSecretario' => 'required|integer|exists:App\Models\MiembroGobierno,id',
            ], [
                // Mensajes error idCentro
                'idCentro.required' => 'El centro es obligatorio.',
                'idCentro.integer' => 'El centro debe ser un entero.',
                'idCentro.exists' => 'El centro seleccionado no existe.',
                // Mensajes error fechaConstitucion
                'fechaConstitucion.required' => 'La fecha de constitución es obligatoria.',
                'fechaConstitucion.date' => 'La fecha de constitución debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error fechaDisolucion
                'fechaDisolucion.date' => 'La fecha de cese debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error director
                'idDirector.required' => 'Es necesario que exista un director/decano actual en el equipo de gobierno del centro para crear una nueva junta.',
                'idDirector.integer' => 'Es necesario que exista un director/decano actual en el equipo de gobierno del centro para crear una nueva junta.',
                'idDirector.exists' => 'El director seleccionado no existe.',
                // Mensajes error secretario
                'idSecretario.required' => 'Es necesario que exista un secretario actual en el equipo de gobierno del centro para crear una nueva junta.',
                'idSecretario.integer' => 'Es necesario que exista un secretario actual en el equipo de gobierno del centro para crear una nueva junta.',
                'idSecretario.exists' => 'El secretario seleccionado no existe.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
            $dateConstitucion = new DateTime($request->fechaConstitucion);
            $dateDisolucion = new DateTime($request->fechaDisolucion);

            if ($dateConstitucion>$dateDisolucion) {
                return redirect()->route('juntas')->with('error', 'La fecha de disolución '.$request->fechaDisolucion.' no puede ser anterior a la fecha de constitución '. $request->fechaConstitucion)->withInput();
            }

            // Comprobación existencia junta en activo para el centro seleccionado
            $junta = Junta::select('id')
                ->where('idCentro', $request->get('idCentro'))
                ->where('fechaDisolucion', null)
                ->where('estado', 1)
                ->first();

            if($junta)
                return redirect()->route('juntas')->with('error', 'No se pudo crear la junta: ya existe una junta en activo para el centro indicado')->withInput();

            $junta = Junta::create([
                "idCentro" => $request->idCentro,
                "fechaConstitucion" => $request->fechaConstitucion,
                "fechaDisolucion" => $request->fechaDisolucion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('juntas')->with('success', 'Junta creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('juntas')->with('error', 'No se pudo crear la junta: ' . $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $junta = Junta::where('id', $request->id)->first();

            if ($request->estado == 0) {
                $junta->estado = 1;
            } else {
                $junta->estado = 0;
            }

            if (!$junta) {
                return response()->json(['error' => 'No se ha encontrado la junta.'], 404);
            }

            $junta->save();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado la junta.'], 404);
        }
    }

    public function get(Request $request)
    {
        try {
            $junta = Junta::where('id', $request->id)->first();
            if (!$junta) {
                return response()->json(['error' => 'No se ha encontrado la junta.'], 404);
            }
            return response()->json($junta);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado la junta.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $junta = Junta::where('id', $request->id)->first();
            if (!$junta) {
                return response()->json(['error' => 'No se ha encontrado la junta.', 'status' => 404], 404);
            }

            // Validar que fechaConstitución no pueda ser mayor a fechaDisolución
            $dateConstitucion = new DateTime($request->data['fechaConstitucion']);
            $dateDisolucion = new DateTime($request->data['fechaDisolucion']);

            if ($dateConstitucion>$dateDisolucion) {
                return response()->json(['error' => 'La fecha de disolución no puede ser anterior a la fecha de constitución de la junta', 'status' => 404], 200);
            } 

            $junta->fechaConstitucion = $request->data['fechaConstitucion'];
            $junta->fechaDisolucion = $request->data['fechaDisolucion'];
            $junta->save();
            return response()->json(['message' => 'La junta se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar la junta.', 'status' => 404], 404);
        }
    }

    public function all()
    {
        try {
            $juntas = DB::table('juntas')
            ->join('centros', 'juntas.idCentro', '=', 'centros.id')
            ->where('juntas.estado', 1)
            ->select('juntas.id', 'centros.nombre')
            ->get();
            if (!$juntas) {
                return response()->json(['error' => 'No se han podido obtener las juntas.'], 404);
            }

            return response()->json($juntas);
        } catch (\Throwable $th) {
            return redirect()->route('juntas')->with('error', 'No se pudieron obtener las juntas: ' . $th->getMessage());
        }
    }
}
