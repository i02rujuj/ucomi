<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CentrosController extends Controller
{
    public function index()
    {
        try {
            $centros = Centro::select('id', 'nombre', 'direccion', 'tipo', 'estado')->get();
            return view('centros', ['centros' => $centros]);
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
                'tipo' => 'required|max:50|string'
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
                'tipo.required' => 'El tipo es obligatorio.',
                'tipo.string' => 'El tipo debe ser una cadena de texto.',
                'tipo.max' => 'El nombre no puede exceder los 50 caracteres.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $centro = Centro::create([
                "nombre" => $request->nombre,
                "direccion" => $request->direccion,
                "tipo" => $request->tipo,
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
}
