<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Junta;
use App\Models\Centro;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JuntasController extends Controller
{
    public function index(Request $request)
    {
        try {

            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion', 'estado');

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $juntas = $juntas->filters($request);
                    break;
                default:
                    $juntas = $juntas->where('estado', 1);
                    break;
            }

            $user = Auth::user();

            if($user->hasRole('admin')){
                $centros = Centro::select('id', 'nombre')->where('estado', 1)->get();

                $juntas = $juntas->orderBy('estado', 'desc')
                ->orderBy('fechaDisolucion')
                ->orderBy('fechaConstitucion', 'desc')
                ->paginate(5);
            }
            
            if($user->hasRole('responsable_centro')){
                $centro = MiembroGobierno::where('miembros_gobierno.idUsuario', $user->id)
                ->join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->join('centros', 'miembros_gobierno.idCentro', '=', 'centros.id')
                ->where('centros.estado', 1)
                ->select('centros.id', 'centros.nombre')
                ->first();

                $juntas = $juntas->where('idCentro', $centro->id)
                ->orderBy('estado', 'desc')
                ->orderBy('fechaDisolucion')
                ->orderBy('fechaConstitucion', 'desc')
                ->paginate(5);

                $centros=array($centro);
            }

            if($user->hasRole('responsable_junta')){
                /* ////////////////////////////////////*/
            }

            if($request->input('action')=='limpiar'){
                return redirect()->route('juntas')->with([
                    'juntas' => $juntas, 
                    'centros' => $centros,
                ]);
            }

            return view('juntas', [
                'juntas' => $juntas, 
                'centros' => $centros,
                'filtroCentro' => $request['filtroCentro'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('juntas')->with('errors', 'No se pudieron obtener las juntas: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    { 
        try {
            $request['accion']='add';
            $validation = $this->validateJunta($request);
            if($validation->original['status']!=200){
                return $validation;
            }
           
            $junta = Junta::create([
                "idCentro" => $request->data['idCentro'],
                "fechaConstitucion" => $request->data['fechaConstitucion'],
                "fechaDisolucion" => $request->data['fechaDisolucion'],
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);

            return response()->json(['message' => 'La junta se ha añadido correctamente.', 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al añadir la junta.', 'status' => 422], 200);
        }
    }

    public function delete(Request $request)
    {
        try {

            $request['accion']='delete';
            $validation = $this->validateJunta($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $junta = Junta::where('id', $request->id)->first();

            if (!$junta) {
                return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
            }

            $junta->estado = 0;
            $junta->save();
            return response()->json(['status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $junta = Junta::where('id', $request->id)->first();
            if (!$junta) {
                return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
            }
            return response()->json($junta);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
        }
    }

    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateJunta($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $junta=Junta::where('id', $request->id)->first();

            if (!$junta) {
                return response()->json(['errors' => 'No se ha encontrado la junta.', 'status' => 422], 200);
            }

            if($request->data['fechaDisolucion']!=null){
                $miembrosJunta = DB::table('miembros_junta')
                ->where('idJunta', $junta->id)
                ->update(['fechaCese' => $request->data['fechaDisolucion']]);
            }

            $junta->fechaConstitucion = $request->data['fechaConstitucion'];
            $junta->fechaDisolucion = $request->data['fechaDisolucion'];
            $junta->save();
            return response()->json(['message' => 'La junta se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al actualizar la junta.', 'status' => 422], 200);
        }
    }

    public function all()
    {
        try {
            $juntas = DB::table('juntas')
            ->join('centros', 'juntas.idCentro', '=', 'centros.id')
            ->where('juntas.estado', 1)
            ->select('juntas.id', 'juntas.fechaConstitucion', 'centros.id as idCentro', 'centros.nombre')
            ->get();

            if (!$juntas) {
                return response()->json(['errors' => 'No se han podido obtener las juntas.','status' => 422], 200);
            }

            return response()->json($juntas);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener las juntas.','status' => 422], 200);
        }
    }

    public function rules()
    {
        $rules = [
            'idCentro' => 'required|integer|exists:App\Models\Centro,id',
            'fechaConstitucion' => 'required|date',
            'fechaDisolucion' => 'nullable|date',
        ]; 
        
        $rules_message = [
            // Mensajes error idCentro
            'idCentro.required' => 'El centro es obligatorio.',
            'idCentro.integer' => 'El centro debe ser un entero.',
            'idCentro.exists' => 'El centro seleccionado no existe.',
            // Mensajes error fechaConstitucion
            'fechaConstitucion.required' => 'La fecha de constitución es obligatoria.',
            'fechaConstitucion.date' => 'La fecha de constitución debe tener el formato fecha DD/MM/YYYY.',
            // Mensajes error fechaDisolucion
            'fechaDisolucion.date' => 'La fecha de cese debe tener el formato fecha DD/MM/YYYY.',
        ];

        return [$rules, $rules_message];
    }

    public function validateJunta(Request $request){

        if($request->accion=='delete'){
            $junta = Junta::where('id', $request->id)->first();

            if (!$junta) {
                return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
            }

            if($junta->miembrosJunta->where('estado', 1)->count() > 0)
                return response()->json(['errors' => 'Existen miembros de junta asociadas a esta junta. Para borrar la junta es necesario eliminar todos sus miembros de junta.', 'status' => 422], 200);

            if($junta->comisiones->where('estado', 1)->count() > 0)
                return response()->json(['errors' => 'Existen comisiones asociadas a esta junta. Para borrar la junta es necesario eliminar todas sus comisiones.', 'status' => 422], 200);

            if($junta->convocatorias->where('estado', 1)->count() > 0)
                return response()->json(['errors' => 'Existen convocatorias asociadas a esta junta. Para borrar la junta es necesario eliminar todas sus convocatorias.', 'status' => 422], 200);

        }
        else{
            $validator = Validator::make($request->data, $this->rules()[0], $this->rules()[1]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first(), 'status' => 422], 200);
            }
            else{
                if($request->data['fechaDisolucion']!=null){
                    // Validar que fechaConstitución no pueda ser mayor a fechaDisolución
                    $dateConstitucion = new DateTime($request->data['fechaConstitucion']);
                    $dateDisolucion = new DateTime($request->data['fechaDisolucion']);
    
                    if ($dateConstitucion>$dateDisolucion) {
                        return response()->json(['errors' => 'La fecha de disolución '.$request->fechaDisolucion.' no puede ser anterior a la fecha de constitución '. $request->fechaConstitucion, 'status' => 422], 200);
                    }
                }
                else{
                    switch($request->accion){
                        case 'add':
                            // Comprobación existencia junta en activo para el centro seleccionado
                            $junta = Junta::select('id')
                                ->where('idCentro', $request->data['idCentro'])
                                ->where('fechaDisolucion', null)
                                ->where('estado', 1)
                                ->first();
                            break;
                        case 'update':
                            $junta = Junta::select('id')
                                ->where('idCentro', $request->data['idCentro'])
                                ->where('fechaDisolucion', null)
                                ->where('estado', 1)
                                ->whereNot('id', $request->id)
                                ->first();
                            break;
                    } 
    
                    if($junta){
                        return response()->json(['errors' => 'No se pudo crear la junta. Ya existe una junta vigente para el centro indicado', 'status' => 422], 200);
                    }
                }
            }
        }
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }
}
