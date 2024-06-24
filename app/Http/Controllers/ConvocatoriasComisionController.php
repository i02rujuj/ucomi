<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Convocatoria;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroComision;
use App\Models\MiembroGobierno;
use App\Models\TipoConvocatoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConvocatoriasComisionController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if($user->hasRole('admin')){

                $comisiones = Comision::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->whereNot('idComision', null)
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

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('comisiones.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::select('convocatorias.*')
                ->whereNot('convocatorias.idComision', null)
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
                ->get();

            }

            if($user->hasRole('responsable_junta')){

                $juntaResponsable = MiembroJunta::where('idUsuario', $user->id)
                ->select('idJunta')
                ->first();

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.id', $juntaResponsable->idJunta)
                ->where('juntas.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->whereNot('idComision', null)
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

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.id', $comisionResponsable->idComision)
                ->where('comisiones.estado', 1)
                ->where('comisiones.fechaDisolucion', null)
                ->get();

                $convocatorias = Convocatoria::where('estado', 1)
                ->whereNot('idComision', null)
                ->where('idComision', $comisionResponsable->idComision)
                ->orderBy('fecha')  
                ->orderBy('hora')          
                ->orderBy('idJunta')
                ->orderBy('idComision')
                ->orderBy('idTipo')
                ->get();
            }
            
            $tipos = TipoConvocatoria::where('estado', 1)->get();

            return view('convocatoriasComision', ['convocatorias' => $convocatorias, 'comisiones' => $comisiones, 'tipos' => $tipos]);
        } catch (\Throwable $th) {
            return redirect()->route('convocatoriasComision')->with('error', 'No se pudieron obtener las convocatorias: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idComision' => 'required|integer|exists:App\Models\Comision,id',
                'idTipo' => 'required|integer|exists:App\Models\TipoConvocatoria,id',
                'lugar' => 'required|max:100|string',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'acta' => 'nullable|max:1000|mimetypes:application/pdf',
            ], [
                // Mensajes error idComision
                'idComision.required' => 'La comisión es obligatoria.',
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
                'acta.mimetypes' => 'El acta debe estar en formato pdf.',
                'acta.max' => 'El nombre del acta no puede exceder los 1000 caracteres.',
                
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $file_acta = $request->file('acta');
            $result=null;

            if ($file_acta) {
                $result = cloudinary()->upload($file_acta->getRealPath(), [
                    'folder' => 'actasComision'
                ])->getPublicId();
            }
           
            $convocatoria = Convocatoria::create([
                "idComision" => $request->idComision,
                "idTipo" => $request->idTipo,
                "lugar" => $request->lugar,
                "fecha" => $request->fecha,
                "hora" => $request->hora,
                "acta" => $result,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('convocatoriasComision')->with('success', 'Convocatoria creada correctamente.');

        } catch (\Throwable $th) {
            return redirect()->route('convocatoriasComision')->with('error', 'No se pudo crear la convocatoria: ' . $th->getMessage());
        }
    }
}
