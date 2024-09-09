<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Models\Junta;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\Representacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de los miembros de junta
 * 
 * @author Javier Ruiz Jurado
 */
class MiembrosJuntaController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve los miembros de junta según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view miembrosJunta.blade.php con los miembros y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener los miembros
     */
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
            ->orderBy('updated_at','desc')
            ->orderBy('fechaCese')
            ->orderBy('idJunta')
            ->orderBy('idRepresentacion')
            ->orderBy('fechaTomaPosesion', 'desc')
            ->orderBy('idUsuario')
            ->paginate(12);

            $users = User::select('id', 'name')
            ->get();
            $representacionesGeneral = Representacion::select('id', 'nombre')
            ->where('deJunta', 1)
            ->get();    
            
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
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener los miembros de junta.']);
        }
    }

    /**
     * @brief Método encargado de guardar un miembro de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de junta se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar el miembro de junta
     */
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

            return response()->json(['message' => "El miembro de junta '{$miembroJunta->usuario->name}' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir el miembro de junta", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar un miembro de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de junta se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar el miembro de junta
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $miembroJunta = MiembroJunta::where('id', $request->id)->first();

            $miembroJunta->idRepresentacion = $request->data['idRepresentacion'];
            $miembroJunta->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembroJunta->fechaCese = $request->data['fechaCese'];  
            $miembroJunta->responsable = $request->data['responsable'];  

            $miembroJunta->save();

            return response()->json(['message' => "El miembro de junta '{$miembroJunta->usuario->name}' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al actualizar el miembro de junta.', 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar un miembro de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de junta se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar el miembro de junta
     */
    public function delete(Request $request)
    {
        $request['accion']='delete';
        $validation = $this->validateMiembro($request);
        if($validation->original['status']!=200){
            return $validation;
        }

        try {
            $miembroJunta = MiembroJunta::where('id', $request->id)->first();
            $miembroJunta->delete();

            return response()->json(['message' => "El miembro de junta '{$miembroJunta->usuario->name}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar el miembro de junta",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener un miembro de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos del miembro de junta a obtener
     * @throws \Throwable Si no se pudo obtener el miembro de junta, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $miembroJunta = MiembroJunta::withTrashed()->where('id', $request->id)->first();
            if (!$miembroJunta) {
                throw new Exception();
            }
            
            return response()->json($miembroJunta);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 500], 200);
        }
    }

    /**
     * @brief Método que establece las reglas de validación, así como los mensajes que serán devueltos en caso de no pasar la validación
     * @return array con las reglas y mensajes de validación
     */
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

    /**
     * @brief Método encargado de validar los datos de un miembro de junta, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de junta se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe el usuario como miembro de la junta, o ya existe un director o secretario en la junta
     * UPDATE: No se ha encontrado el miembro a actualizar o no ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe un director o secretario en la junta
     * DELETE: No se ha encontrado el miembro a eliminar.
     */
    public function validateMiembro(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $miembroJunta = MiembroJunta::where('id', $request->id)->first();

            if (!$miembroJunta) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de junta.','status' => 422], 200);
            }
        }

        if($request->accion!='delete'){

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
        }
        
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }
}
