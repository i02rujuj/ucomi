<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Comision;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroComision;
use Illuminate\Support\Facades\Auth;
use App\Models\RepresentacionGeneral;
use Illuminate\Support\Facades\Validator;

class MiembrosComisionController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if($user->hasRole('admin')){
                
                $comisiones = Comision::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();
    
                $miembrosComision = MiembroComision::where('estado',1)
                ->orderBy('fechaCese')
                ->orderBy('idComision')
                ->orderBy('idRepresentacion')
                ->orderBy('idUsuario')
                ->get();
            }

            if($user->hasRole('responsable_junta')){

                $juntaResponsable = MiembroJunta::where('idUsuario', $user->id)
                ->select('idJunta')
                ->first();

                $comisiones = Comision::where('estado', 1)
                ->where('idJunta', $juntaResponsable->idJunta)
                ->where('fechaDisolucion', null)
                ->get();
    
                $miembrosComision = MiembroComision::select('miembros_comision.*')
                ->where('miembros_comision.estado', 1)
                ->join('comisiones', 'comisiones.id', '=', 'miembros_comision.idComision')
                ->where('comisiones.idJunta', $juntaResponsable->idJunta)
                ->where('miembros_comision.estado',1)
                ->orderBy('miembros_comision.fechaCese')
                ->orderBy('miembros_comision.idComision')
                ->orderBy('miembros_comision.idRepresentacion')
                ->orderBy('miembros_comision.idUsuario')
                ->get();
            }

            $users = User::select('id', 'name')->where('estado', 1)->get();
            $representacionesGeneral = RepresentacionGeneral::select('id', 'nombre')->where('estado', 1)->get();

            return view('miembrosComision', ['comisiones' => $comisiones, 'users' => $users, 'representacionesGeneral' => $representacionesGeneral, 'miembrosComision' => $miembrosComision]);
        } catch (\Throwable $th) {
            return redirect()->route('miembrosComision')->with('error', 'No se pudieron obtener algunos datos referentes a los miembros de Comisión: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idComision' => 'required|integer|exists:App\Models\Comision,id',
                'idUsuario' => 'required|integer|exists:App\Models\User,id',
                'fechaTomaPosesion' => 'required|date',
                'fechaCese' => 'nullable|date',
                'idRepresentacion' => 'required|integer|exists:App\Models\RepresentacionGeneral,id',
            ], [
                // Mensajes error idComision
                'idComision.required' => 'La comisión es obligatoria.',
                'idComision.integer' => 'La comisión debe ser un entero.',
                'idComision.exists' => 'La comisión seleccionada no existe.',
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

            if($request->fechaCese != null){
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateTomaPosesion = new DateTime($request->fechaTomaPosesion);
                $dateCese = new DateTime($request->fechaCese);

                if ($dateTomaPosesion>$dateCese) 
                    return redirect()->route('miembrosComision')->with('error', 'La fecha de cese no puede ser anterior a la toma de posesión')->withInput();
            }

            // Comprobación existencia miembro repetido en la misma comisión
            $miembroRepetido = MiembroComision::select('id')
                        ->where('idComision', $request->get('idComision'))
                        ->where('idUsuario', $request->get('idUsuario'))
                        ->where('fechaCese', null)
                        ->where('estado', 1)
                        ->first();
    
            if($miembroRepetido)
                return redirect()->route('miembrosComision')->with('error', 'No se pudo crear el miembro de Comisión: ya existe un miembro en activo para la comisión seleccionada')->withInput();
                

            $miembrosComision = MiembroComision::create([
                "idComision" => $request->idComision,
                "idUsuario" => $request->idUsuario,
                "fechaTomaPosesion" => $request->fechaTomaPosesion,
                "fechaCese" => $request->fechaCese,
                "idRepresentacion" => $request->idRepresentacion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('miembrosComision')->with('success', 'Miembro de Comisión creado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('miembrosComision')->with('error', 'No se pudo crear el miembro de comisión: ' . $th->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $miembro = MiembroComision::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de comisión.'], 404);
            }

            return response()->json($miembro);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de comisión.'], 404);
        }
    }

    public function delete(Request $request)
    {
        try {
            $miembro = MiembroComision::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Comisión.'], 404);
            }

            $miembro->estado = 0;
            $miembro->save();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el miembro de Comisión.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $miembro = MiembroComision::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['error' => 'No se ha encontrado el miembro de Comisión', 'status' => 404], 200);
            }

            if($request->data['fechaCese'] != null){
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateTomaPosesion = new DateTime($request->data['fechaTomaPosesion']);
                $dateCese = new DateTime($request->data['fechaCese']);

                if ($dateTomaPosesion>$dateCese) 
                    return response()->json(['error' => 'La fecha de cese no puede ser anterior a la toma de posesión', 'status' => 404], 200);
            }
            else{
                // Comprobación existencia usuario vigente en la comisión
                $miembroRepetido = MiembroComision::select('id')
                ->where('idComision', $miembro->idComision)
                ->where('idUsuario', $miembro->idUsuario)
                ->where('fechaCese', null)
                ->where('estado', 1)
                ->count();

                if($miembroRepetido>1)
                    return response()->json(['error' => 'No se pudo editar el miembro de la comisión: ya existe el usuario vigente en la comisión seleccionada', 'status' => 404], 200);
            }

            if($request->data['responsable'] == 0){
                $miembro->usuario->removeRole('responsable_comision');
            }
            else{
                $miembro->usuario->assignRole('responsable_comision');
            }

            $miembro->idRepresentacion = $request->data['idRepresentacion'];
            $miembro->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembro->fechaCese = $request->data['fechaCese'];  
            $miembro->save();
            return response()->json(['message' => 'El miembro de Comisión se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar el miembro de comisión.', 'status' => 404], 404);
        }
    }
}
