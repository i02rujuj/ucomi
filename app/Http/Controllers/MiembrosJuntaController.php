<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Junta;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Representacion;
use Illuminate\Support\Facades\Validator;

class MiembrosJuntaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $miembrosJunta = MiembroJunta::select('id', 'idJunta', 'idUsuario', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaConstitucion');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $juntas = $juntas->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                $miembrosJunta = $miembrosJunta
                ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                });
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $juntas = $juntas->where('id', $datosResponsableJunta['idJuntas']);
                $miembrosJunta = $miembrosJunta
                ->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroJunta']=null;
                    $request['filtroRepresentacion']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $miembrosJunta = $miembrosJunta->withTrashed()->filters($request);
                    break;
                default:
                    $miembrosJunta = $miembrosJunta->whereNull('deleted_at');
                    break;
            }

            $juntas = $juntas
            ->get();

            $miembrosJunta = $miembrosJunta
            ->orderBy('deleted_at')
            ->orderBy('fechaCese')
            ->orderBy('updated_at','desc')
            ->orderBy('idJunta')
            ->orderBy('idRepresentacion')
            ->orderBy('idUsuario')
            ->paginate(10);

            $users = User::select('id', 'name')->get();
            $representacionesGeneral = Representacion::select('id', 'nombre')->get();    
            
            if($request->input('action')=='limpiar'){
                return redirect()->route('miembrosJunta')->with([
                    'juntas' => $juntas, 
                    'users' => $users, 
                    'representacionesGeneral' => $representacionesGeneral, 
                    'miembrosJunta' => $miembrosJunta,
                ]);
            }

            return view('miembrosJunta', [
                'juntas' => $juntas, 
                'users' => $users, 
                'representacionesGeneral' => $representacionesGeneral, 
                'miembrosJunta' => $miembrosJunta,
                'filtroJunta' => $request['filtroJunta'],
                'filtroRepresentacion' => $request['filtroRepresentacion'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);
        
        } catch (\Throwable $th) {
            return redirect()->route('miembrosJunta')->with('errors', 'No se pudieron obtener los miembros de junta: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request['accion']='add';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            } 

            $miembroJunta = MiembroJunta::create([
                "idJunta" => $request->data['idJunta'],
                "idUsuario" => $request->data['idUsuario'],
                "fechaTomaPosesion" => $request->data['fechaTomaPosesion'],
                "fechaCese" => $request->data['fechaCese'],
                "idRepresentacion" => $request->data['idRepresentacion'],
                "responsable" => $request->data['responsable'],
            ]);

            return response()->json(['message' => 'Miembro de junta creado correctamente.', 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se pudo crear el miembro de junta.', 'status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $miembro = MiembroJunta::withTrashed()->where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 422], 200);
            }
            return response()->json($miembro);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 422], 200);
        }
    }

    public function delete(Request $request)
    {
        try {
            $miembro = MiembroJunta::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 422], 200);
            }

            $miembro->delete();
            return response()->json(['status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 422], 200);
        }
    }

    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $miembro = MiembroJunta::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de junta.', 'status' => 422], 200);
            }

            $miembro->idRepresentacion = $request->data['idRepresentacion'];
            $miembro->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembro->fechaCese = $request->data['fechaCese'];  
            $miembro->responsable = $request->data['responsable'];  

            $miembro->save();
            return response()->json(['message' => 'El miembro de junta se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al actualizar el miembro de junta.', 'status' => 422], 200);
        }
    }

    public function rules(){
        $rules = [
            'idJunta' => 'required|integer|exists:App\Models\Junta,id',
            'idUsuario' => 'required|integer|exists:App\Models\User,id',
            'idRepresentacion' => 'required|integer|exists:App\Models\Representacion,id',
            'fechaTomaPosesion' => 'required|date',
            'fechaCese' => 'nullable|date',
        ];

        $rules_message = [
            // Mensajes error idJunta
            'idJunta.required' => 'La junta es obligatoria.',
            'idJunta.integer' => 'La junta debe ser un entero.',
            'idJunta.exists' => 'La junta seleccionada no existe.',
            // Mensajes error idUsuario
            'idUsuario.required' => 'El usuario es obligatorio.',
            'idUsuario.integer' => 'El usuario debe ser un entero.',
            'idUsuario.exists' => 'El usuario seleccionado no existe.',
            // Mensajes error idRepresentacion
            'idRepresentacion.required' => 'La representación es obligatoria.',
            'idRepresentacion.integer' => 'La representación debe ser un entero.',
            'idRepresentacion.exists' => 'La representación seleccionada no existe.',
            // Mensajes error fechaTomaPosesión
            'fechaTomaPosesion.required' => 'La fecha de toma de posesión es obligatoria.',
            'fechaTomaPosesion.date' => 'La fecha de toma de posesión debe tener el formato fecha DD/MM/YYYY.',
            // Mensajes error fechaCese
            'fechaCese.date' => 'La fecha de cese debe tener el formato fecha DD/MM/YYYY.',
        ];

        return [$rules, $rules_message];
    }

    public function validateMiembro(Request $request){

        $validator = Validator::make($request->data, $this->rules()[0], $this->rules()[1]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first(), 'status' => 422], 200);
        }
        else{
            if($request->data['fechaCese']==null){
                // Comprobación existencia director actual en la junta
                if($request->data['idRepresentacion']==config('constants.REPRESENTACIONES.GENERAL.DIRECTOR')){
                    $director = MiembroJunta::select('id')
                        ->where('idJunta', $request->data['idJunta'])
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GENERAL.DIRECTOR'))
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();
    
                    if($director)
                        return response()->json(['errors' => 'No se pudo añadir el miembro de junta: ya existe un Director/a | Decano/a vigente en la junta seleccionada', 'status' => 422], 200);
                }
    
                // Comprobación existencia secretario actual en la junta
                if($request->data['idRepresentacion']==config('constants.REPRESENTACIONES.GENERAL.SECRETARIO')){
                    $secretario = MiembroJunta::select('id')
                        ->where('idJunta', $request->data['idJunta'])
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GENERAL.SECRETARIO'))
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();
    
                    if($secretario)
                        return response()->json(['errors' => 'No se pudo añadir el miembro de junta: ya existe un Secretario/a vigente en el centro seleccionado', 'status' => 422], 200);
                }

                // Comprobación existencia usuario en la junta
                $usuarioEnJunta = MiembroJunta::select('id')
                    ->where('idJunta', $request->data['idJunta'])
                    ->where('idUsuario', $request->data['idUsuario'])
                    ->where('fechaCese', null)
                    ->whereNot('id', $request->id)
                    ->first();

                if($usuarioEnJunta)
                    return response()->json(['errors' => 'No se pudo añadir el miembro de junta: ya existe el usuario vigente en la junta seleccionado', 'status' => 422], 200);
            
                if($request->accion=='update'){
                    // Comprobación existencia junta vigente
                    $juntaVigenteMiembro = Junta::where('id', $request->data['idJunta'])
                    ->whereNotNull('fechaDisolucion')
                    ->first();

                    if($juntaVigenteMiembro)
                        return response()->json(['errors' => 'No se pudo actualizar el miembro de junta: la junta no está vigente', 'status' => 422], 200);
                }
            }
            else{
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateTomaPosesion = new DateTime($request->data['fechaTomaPosesion']);
                $dateCese = new DateTime($request->data['fechaCese']);

                if ($dateTomaPosesion>$dateCese) {
                    return response()->json(['errors' => 'La fecha de cese no puede ser anterior a la toma de posesión', 'status' => 422], 200);
                }  
            }
        }
        
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }

    public function getByCentro(Request $request)
    {
        try {
            $miembros = DB::table('miembros_junta')
                ->join('users', 'miembros_junta.idUsuario', '=', 'users.id')
                ->join('representaciones_general', 'miembros_junta.idRepresentacion', '=', 'representaciones_general.id')
                ->join('juntas', 'miembros_junta.idJunta', '=', 'juntas.id')
                ->where('juntas.idCentro', $request->get('id'))
                ->where('miembros_junta.fechaCese', null)
                ->where('miembros_junta.estado', 1)
                ->select('users.id', 'users.name', 'users.email', 'miembros_junta.idRepresentacion', 'representaciones_general.nombre')
                ->get();

            return response()->json(['miembros'=>$miembros]);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han encontrado miembros de la junta para el centro seleccionado.', 'status' => 422], 200);
        }    
    }
}
