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

class ConvocatoriasController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if($user->hasRole('admin')){

                $juntas = Junta::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();

                $comisiones = Comision::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
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

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('comisiones.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatoriasJuntas = Convocatoria::select('convocatorias.*')
                ->where('convocatorias.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'convocatorias.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('juntas.estado', 1)
                ->orderBy('convocatorias.fecha')  
                ->orderBy('convocatorias.hora')          
                ->orderBy('convocatorias.idJunta')
                ->orderBy('convocatorias.idComision')
                ->orderBy('convocatorias.idTipo');

                $convocatorias = Convocatoria::select('convocatorias.*')
                ->where('convocatorias.estado', 1)
                ->join('comisiones', 'comisiones.id', '=', 'convocatorias.idComision')
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('juntas.estado', 1)
                ->orderBy('convocatorias.fecha')  
                ->orderBy('convocatorias.hora')          
                ->orderBy('convocatorias.idJunta')
                ->orderBy('convocatorias.idComision')
                ->orderBy('convocatorias.idTipo')
                ->union($convocatoriasJuntas)
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

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.id', $juntaResponsable->idJunta)
                ->where('juntas.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->where('idJunta', $juntaResponsable->idJunta)
                ->orderBy('fecha')  
                ->orderBy('hora')          
                ->orderBy('idJunta')
                ->orderBy('idComision')
                ->orderBy('idTipo')
                ->get();

            }

            if($user->hasRole('responsable_comision')){

               $comisionResponsable = MiembroComision::where('idUsuario', $user->id)
                ->select('idComision')
                ->first();

                $juntas = Junta::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.id', $comisionResponsable->idComision)
                ->where('comisiones.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->where('idComision', $comisionResponsable->idComision)
                ->orderBy('fecha')  
                ->orderBy('hora')          
                ->orderBy('idJunta')
                ->orderBy('idComision')
                ->orderBy('idTipo')
                ->get();
            }
            
            $tipos = TipoConvocatoria::where('estado', 1)->get();

            return view('convocatorias', ['convocatorias' => $convocatorias, 'juntas' => $juntas, 'comisiones' => $comisiones, 'tipos' => $tipos]);
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

            if(!$request->idJunta && !$request->idComision){
                return redirect()->route('convocatorias')->with('error', 'Debe seleccionar una junta o comisión para poder crear la convocatoria')->withInput();
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
