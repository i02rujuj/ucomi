<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Models\Convocatoria;
use Illuminate\Http\Request;
use App\Models\TipoConvocatoria;
use Illuminate\Support\Facades\Validator;

class ConvocatoriasController extends Controller
{
    public function index()
    {
        try {
            $convocatorias = Convocatoria::where('estado', 1)
            ->orderBy('fecha')  
            ->orderBy('hora')          
            ->orderBy('idJunta')
            ->orderBy('idComision')
            ->orderBy('idTipo')
            ->get();

            $juntas = Junta::where('estado', 1)
            ->where('fechaDisolucion', null)
            ->get();
            
            $tipos = TipoConvocatoria::where('estado', 1)->get();

            return view('convocatorias', ['convocatorias' => $convocatorias, 'juntas' => $juntas, 'tipos' => $tipos]);
        } catch (\Throwable $th) {
            return redirect()->route('convocatorias')->with('error', 'No se pudieron obtener las convocatorias: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idJunta' => 'nullable|integer|exists:App\Models\Junta,id',
                'idComision' => 'nullable|integer|exists:App\Models\Comision,id',
                'idTipo' => 'required|integer|exists:App\Models\TipoConvocatoria,id',
                'lugar' => 'required|max:100|string',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'acta' => 'nullable|max:100|string',
            ], [
                // Mensajes error idJunta
                'idJunta.integer' => 'La junta debe ser un entero.',
                'idJunta.exists' => 'La junta seleccionada no existe.',
                // Mensajes error idComision
                'idComision.integer' => 'La comisión debe ser un entero.',
                'idComision.exists' => 'La comisión seleccionada no existe.',
                // Mensajes error idTipo
                'idTipo.integer' => 'El tipo de convocatoria debe ser un entero.',
                'idTipo.exists' => 'El tipo de convocatoria seleccionado no existe.',
                // Mensajes error lugar
                'lugar.required' => 'El lugar es obligatorio.',
                'lugar.string' => 'El lugar no puede contener números ni caracteres especiales.',
                'lugar.max' => 'El lugar no puede exceder los 100 caracteres.',
                // Mensajes error fecha
                'fecha.required' => 'La fecha es obligatoria.',
                'fecha.date' => 'La fecha debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error hora
                'hora.required' => 'La hora es obligatoria.',
                'hora.date_format' => 'La hora debe tener el formato hora HH:MM.',
                // Mensajes error acta
                'acta.string' => 'El acta no puede contener números ni caracteres especiales.',
                'acta.max' => 'El acta no puede exceder los 100 caracteres.',
                
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }
           
            $convocatoria = Convocatoria::create([
                "idJunta" => $request->idJunta,
                "idComision" => $request->idComision,
                "idTipo" => $request->idTipo,
                "lugar" => $request->lugar,
                "fecha" => $request->fecha,
                "hora" => $request->hora,
                "acta" => $request->acta,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('convocatorias')->with('success', 'Convocatoria creada correctamente.');

        } catch (\Throwable $th) {
            return redirect()->route('convocatorias')->with('error', 'No se pudo crear la convocatoria: ' . $th->getMessage());
        }
    }
}
