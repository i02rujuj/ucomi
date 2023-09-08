<?php

namespace App\Http\Controllers;

use App\Models\Junta;
use App\Models\Comision;
use App\Models\Convocatoria;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroComision;
use App\Models\MiembroGobierno;
use App\Models\TipoConvocatoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConvocatoriasJuntaController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if($user->hasRole('admin')){

                $juntas = Junta::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->whereNot('idJunta', null)
                ->orderBy('fecha')  
                ->orderBy('hora')          
                ->orderBy('idJunta')
                ->orderBy('idComision')
                ->orderBy('idTipo')
                ->get();
            }

            if($user->hasRole('responsable_centro')){

                $centroResponsable = MiembroGobierno::where('idUsuario', $user->id)
                ->select('idCentro')
                ->first();

                $juntas = Junta::where('estado', 1)
                ->where('idCentro', $centroResponsable->idCentro)
                ->where('fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::select('convocatorias.*')
                ->where('convocatorias.estado', 1)
                ->whereNot('convocatorias.idJunta', null)
                ->join('juntas', 'juntas.id', '=', 'convocatorias.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('juntas.estado', 1)
                ->orderBy('convocatorias.fecha')  
                ->orderBy('convocatorias.hora')          
                ->orderBy('convocatorias.idJunta')
                ->orderBy('convocatorias.idComision')
                ->orderBy('convocatorias.idTipo')
                ->get();
            }

            if($user->hasRole('responsable_junta')){

                $juntaResponsable = MiembroJunta::where('idUsuario', $user->id)
                ->select('idJunta')
                ->first();

                $juntas = Junta::where('estado', 1)
                ->where('id', $juntaResponsable->idJunta)
                ->where('fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->whereNot('idJunta', null)
                ->where('idJunta', $juntaResponsable->idJunta)
                ->orderBy('fecha')  
                ->orderBy('hora')          
                ->orderBy('idJunta')
                ->orderBy('idComision')
                ->orderBy('idTipo')
                ->get();

            }
            
            $tipos = TipoConvocatoria::where('estado', 1)->get();

            return view('convocatoriasJunta', ['convocatorias' => $convocatorias, 'juntas' => $juntas, 'tipos' => $tipos]);
        } catch (\Throwable $th) {
            return redirect()->route('convocatoriasJunta')->with('error', 'No se pudieron obtener las convocatorias: ' . $th->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idJunta' => 'required|integer|exists:App\Models\Junta,id',
                'idTipo' => 'required|integer|exists:App\Models\TipoConvocatoria,id',
                'lugar' => 'required|max:100|string',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'acta' => 'nullable|max:100|string',
            ], [
                // Mensajes error idJunta
                'idJunta.required' => 'La junta es obligatoria.',
                'idJunta.integer' => 'La junta debe ser un entero.',
                'idJunta.exists' => 'La junta seleccionada no existe.',
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
                "idTipo" => $request->idTipo,
                "lugar" => $request->lugar,
                "fecha" => $request->fecha,
                "hora" => $request->hora,
                "acta" => $request->acta,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('convocatoriasJunta')->with('success', 'Convocatoria creada correctamente.');

        } catch (\Throwable $th) {
            return redirect()->route('convocatoriasJunta')->with('error', 'No se pudo crear la convocatoria: ' . $th->getMessage());
        }
    }
}
