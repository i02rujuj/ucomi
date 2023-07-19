<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JuntasController extends Controller
{
    public function index()
    {
        try {
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'estado')->get();
            $centros = Centro::select('id', 'nombre', 'direccion', 'tipo', 'estado')->get();
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
            ], [
                // Mensajes error idCentro
                'idCentro.required' => 'El centro es obligatorio.',
                'idCentro.integer' => 'El centro debe ser un entero.',
                'idCentro.unique' => 'El centro seleccionado no existe.',
                // Mensajes error fechaConstitucion
                'fechaConstitucion.required' => 'La fecha de constitución es obligatoria.',
                'fechaConstitucion.date' => 'La fecha de constitución debe tener el formato fecha DD/MM/YYYY.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $junta = Junta::create([
                "idCentro" => $request->idCentro,
                "fechaConstitucion" => $request->fechaConstitucion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('juntas')->with('success', 'Junta creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('juntas')->with('error', 'No se pudo crear la junta: ' . $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        /*try {
            $centro = centro::where('id', $request->id)->first();

            if ($request->estado == 0) {
                $centro->estado = 1;
            } else {
                $centro->estado = 0;
            }

            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
            }

            $centro->save();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
        }*/
    }

    public function get(Request $request)
    {
        /*try {
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
            }
            return response()->json($centro);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
        }*/
    }

    public function update(Request $request)
    {
        /*try {
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.', 'status' => 404], 404);
            }
            $centro->nombre = $request->data['nombre'];
            $centro->direccion = $request->data['domicilio'];
            $centro->tipo = $request->data['tipo'];
            $centro->save();
            return response()->json(['message' => 'El centro se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar el centro.', 'status' => 404], 404);
        }*/
    }
}
