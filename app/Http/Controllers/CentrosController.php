<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CentrosController extends Controller
{
    public function index()
    {
        try {
            $centros = Centro::select('id', 'nombre', 'direccion', 'idTipo', 'estado')->get();
            $tiposCentro = TipoCentro::select('id', 'nombre')->get();
            return view('centros', ['centros' => $centros, 'tiposCentro' => $tiposCentro]);
        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudieron obtener los centros: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'nombre' => 'required|max:100|string',
                'direccion' => 'required|string|max:150',
                'idTipo' => 'required|integer|exists:App\Models\TipoCentro,id',
            ], [
                // Mensajes error nombre
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre no puede contener números ni caracteres especiales.',
                'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
                // Mensajes error dirección
                'direccion.required' => 'La dirección es obligatoria.',
                'direccion.string' => 'La dirección debe ser una cadena de texto.',
                'direccion.max' => 'La dirección no puede exceder los 150 carácteres.',
                // Mensajes error tipo
                'idTipo.required' => 'El tipo es obligatorio.',
                'idTipo.integer' => 'El tipo debe ser un entero',
                'idTipo.exixts' => 'El tipo no existe.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $centro = Centro::create([
                "nombre" => $request->nombre,
                "direccion" => $request->direccion,
                "idTipo" => $request->idTipo,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('centros')->with('success', 'Centro creado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudo crear el centro: ' . $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
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
        }
    }

    public function get(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
            }
            return response()->json($centro);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.', 'status' => 404], 404);
            }
            $centro->nombre = $request->data['nombre'];
            $centro->direccion = $request->data['domicilio'];
            $centro->idTipo = $request->data['idTipo'];
            $centro->save();
            return response()->json(['message' => 'El centro se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar el centro.', 'status' => 404], 404);
        }
    }
}
