<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;
use App\Models\RepresentacionGobierno;
use Illuminate\Support\Facades\Validator;

class MiembrosGobiernoController extends Controller
{

    public function index(Request $request)
    {
        try {
            $miembrosGobierno = MiembroGobierno::select('id', 'idCentro', 'idUsuario', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
            $centros = Centro::select('id', 'nombre');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $centros = $centros->where('id', $datosResponsableCentro['idCentros']);
                $miembrosGobierno = $miembrosGobierno
                ->whereIn('idCentro', $datosResponsableCentro['idCentros']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroRepresentacion']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $miembrosGobierno = $miembrosGobierno->withTrashed()->filters($request);
                    break;
                default:
                    $miembrosGobierno = $miembrosGobierno->whereNull('deleted_at');
                    break;
            }

            $centros = $centros->get();

            $miembrosGobierno = $miembrosGobierno
            ->orderBy('deleted_at')
            ->orderBy('fechaCese')
            ->orderBy('updated_at','desc')
            ->orderBy('idCentro')
            ->orderBy('idRepresentacion')
            ->orderBy('idUsuario')
            ->paginate(10);

            $users = User::select('id', 'name')->get();
            $representacionesGobierno = RepresentacionGobierno::select('id', 'nombre')->get();    
            
            if($request->input('action')=='limpiar'){
                return redirect()->route('miembrosGobierno')->with([
                    'centros' => $centros, 
                    'users' => $users, 
                    'representacionesGobierno' => $representacionesGobierno, 
                    'miembrosGobierno' => $miembrosGobierno,
                ]);
            }

            return view('miembrosGobierno', [
                'centros' => $centros, 
                'users' => $users, 
                'representacionesGobierno' => $representacionesGobierno, 
                'miembrosGobierno' => $miembrosGobierno,
                'filtroCentro' => $request['filtroCentro'],
                'filtroRepresentacion' => $request['filtroRepresentacion'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);
        
        } catch (\Throwable $th) {
            return redirect()->route('miembrosGobierno')->with('errors', 'No se pudieron obtener los miembros de centro: ' . $th->getMessage());
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

            $miembroGobierno = MiembroGobierno::create([
                "idCentro" => $request->data['idCentro'],
                "idUsuario" => $request->data['idUsuario'],
                "fechaTomaPosesion" => $request->data['fechaTomaPosesion'],
                "fechaCese" => $request->data['fechaCese'],
                "idRepresentacion" => $request->data['idRepresentacion'],
                "responsable" => $request->data['responsable'],
            ]);

            return response()->json(['message' => 'Miembro de centro creado correctamente.', 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se pudo crear el miembro de centro.', 'status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $miembro = MiembroGobierno::withTrashed()->where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de centro.','status' => 422], 200);
            }
            return response()->json($miembro);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de centro.','status' => 422], 200);
        }
    }

    public function delete(Request $request)
    {
        try {
            $miembro = MiembroGobierno::where('id', $request->id)->first();

            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de centro.','status' => 422], 200);
            }

            $miembro->delete();
            return response()->json(['status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de centro.','status' => 422], 200);
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

            $miembro = MiembroGobierno::where('id', $request->id)->first();
            if (!$miembro) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de centro.', 'status' => 422], 200);
            }

            $miembro->idRepresentacion = $request->data['idRepresentacion'];
            $miembro->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembro->fechaCese = $request->data['fechaCese'];  
            $miembro->responsable = $request->data['responsable'];  

            $miembro->save();
            return response()->json(['message' => 'El miembro de centro se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al actualizar el miembro de centro.', 'status' => 422], 200);
        }
    }

    public function rules()
    {
        $rules = [
            'idCentro' => 'required|integer|exists:App\Models\Centro,id',
            'idUsuario' => 'required|integer|exists:App\Models\User,id',
            'idRepresentacion' => 'required|integer|exists:App\Models\RepresentacionGobierno,id',
            'fechaTomaPosesion' => 'required|date',
            'fechaCese' => 'nullable|date',
            'responsable' => 'nullable|integer'
        ]; 
        
        $rules_message = [
            // Mensajes error idCentro
            'idCentro.required' => 'El centro es obligatorio.',
            'idCentro.integer' => 'El centro debe ser un entero.',
            'idCentro.exists' => 'El centro seleccionado no existe.',
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
            // Mensajes error responsable
            'responsable.integer' => 'El responsable seleccionado no es correcto.',
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
                /// Comprobación existencia director actual en el centro
                if($request->data['idRepresentacion']==config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR')){
                    $director = MiembroGobierno::select('id')
                        ->where('idCentro', $request->data['idCentro'])
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.DIRECTOR'))
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();
    
                    if($director)
                        return response()->json(['errors' => 'No se pudo añadir el miembro de centro: ya existe un Director/a | Decano/a vigente en el centro seleccionado', 'status' => 422], 200);
                }
    
                // Comprobación existencia secretario actual en el centro
                if($request->data['idRepresentacion']==config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO')){
                    $secretario = MiembroGobierno::select('id')
                        ->where('idCentro', $request->data['idCentro'])
                        ->where('idRepresentacion', config('constants.REPRESENTACIONES.GOBIERNO.SECRETARIO'))
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();
    
                    if($secretario)
                        return response()->json(['errors' => 'No se pudo añadir el miembro de centro: ya existe un Secretario/a vigente en el centro seleccionado', 'status' => 422], 200);
                }

                // Comprobación existencia usuario en el centro
                    $usuarioEnCentro = MiembroGobierno::select('id')
                        ->where('idCentro', $request->data['idCentro'])
                        ->where('idUsuario', $request->data['idUsuario'])
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();
    
                    if($usuarioEnCentro)
                        return response()->json(['errors' => 'No se pudo añadir el miembro de centro: ya existe el usuario vigente en el centro seleccionado', 'status' => 422], 200);
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
            $miembros = MiembroGobierno::
                join('users', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->join('representaciones_gobierno', 'miembros_gobierno.idRepresentacion', '=', 'representaciones_gobierno.id')
                ->where('miembros_gobierno.idCentro', $request->get('id'))
                ->where('miembros_gobierno.fechaCese', null)
                ->select('users.id', 'users.name', 'users.email', 'miembros_gobierno.idRepresentacion', 'representaciones_gobierno.nombre')
                ->get();

            return response()->json(['miembros'=>$miembros]);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han encontrado miembros de gobierno para el centro seleccionado.','status' => 422], 200);
        }    
    }
}
