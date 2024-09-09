<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Models\Centro;
use Illuminate\Http\Request;
use App\Models\Representacion;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de los miembros de gobierno
 * 
 * @author Javier Ruiz Jurado
 */
class MiembrosGobiernoController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve los miembros de gobierno según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view miembrosGobierno.blade.php con los miembros y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener los miembros
     */
    public function index(Request $request)
    {
        try {
            $miembrosGobierno = MiembroGobierno::select('id', 'idCentro', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
            $centros = Centro::select('id', 'nombre');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $miembrosGobierno = $miembrosGobierno
                ->whereIn('idCentro', $datosResponsableCentro['idCentros']);

                $centros = $centros->whereIn('id', $datosResponsableCentro['idCentros']);
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
            ->orderBy('updated_at','desc')
            ->orderBy('fechaCese')
            ->orderBy('idCentro')
            ->orderBy('idRepresentacion')
            ->orderBy('fechaTomaPosesion', 'desc')
            ->orderBy('idUsuario')
            ->paginate(12);

            $users = User::select('id', 'name')
            ->get();

            $representacionesGobierno = Representacion::select('id', 'nombre')
            ->where('deCentro', 1)
            ->get();    
            
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
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener los miembros de gobierno.']);
        }
    }

    /**
     * @brief Método encargado de guardar un miembro de gobierno si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de gobierno se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar el miembro de gobierno
     */
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
                "cargo" => $request->data['cargo'],
                "responsable" => $request->data['responsable'],
            ]);

            return response()->json(['message' => "El miembro de gobierno '{$miembroGobierno->usuario->name}' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir el miembro de gobierno", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar un miembro de gobierno si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de gobierno se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar el miembro de gobierno
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $miembroGobierno = MiembroGobierno::where('id', $request->id)->first();

            $miembroGobierno->idRepresentacion = $request->data['idRepresentacion'];
            $miembroGobierno->cargo = $request->data['cargo'];
            $miembroGobierno->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembroGobierno->fechaCese = $request->data['fechaCese'];  
            $miembroGobierno->responsable = $request->data['responsable'];  

            $miembroGobierno->save();

            return response()->json(['message' => "El miembro de gobierno '{$miembroGobierno->usuario->name}' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar el miembro de gobierno '{$miembroGobierno->usuario->name}'".$th->getMessage(), 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar un miembro de gobierno si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de gobierno se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar el miembro de gobierno
     */
    public function delete(Request $request)
    {
        $request['accion']='delete';
        $validation = $this->validateMiembro($request);
        if($validation->original['status']!=200){
            return $validation;
        }

        try {
            $miembroGobierno = MiembroGobierno::where('id', $request->id)->first();
            $miembroGobierno->delete();

            return response()->json(['message' => "El miembro de gobierno '{$miembroGobierno->usuario->name}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar el miembro de gobierno",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener un miembro de gobierno si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos del miembro de gobierno a obtener
     * @throws \Throwable Si no se pudo obtener el miembro de gobierno, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $miembroGobierno = MiembroGobierno::withTrashed()->where('id', $request->id)->first();
            if (!$miembroGobierno) {
                throw new Exception();
            }

            return response()->json($miembroGobierno);
        } catch (\Throwable $th) {
            return response()->json(['errors' => "No se ha encontrado el miembro de gobierno.",'status' => 500], 200);
        }
    }

    /**
     * @brief Método que establece las reglas de validación, así como los mensajes que serán devueltos en caso de no pasar la validación
     * @return array con las reglas y mensajes de validación
     */
    public function rules()
    {
        $rules = [
            'idCentro' => 'required|integer|exists:App\Models\Centro,id',
            'idUsuario' => 'required|integer|exists:App\Models\User,id',
            'idRepresentacion' => 'required|integer|exists:App\Models\Representacion,id',
            'cargo' => 'nullable|max:100|string',
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
            // Mensajes error cargo
            'cargo.string' => 'El cargo no puede contener números ni caracteres especiales.',
            'cargo.max' => 'El cargo no puede exceder los 100 caracteres.',
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

    /**
     * @brief Método encargado de validar los datos de un miembro de gobierno, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de gobierno se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe el usuario como miembro del centro, o ya existe un director o secretario en el centro
     * UPDATE: No se ha encontrado el miembro a actualizar o no ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe un director o secretario en el centro
     * DELETE: No se ha encontrado el miembro a eliminar.
     */
    public function validateMiembro(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $miembroGobierno = MiembroGobierno::where('id', $request->id)->first();

            if (!$miembroGobierno) {
                return response()->json(['errors' => 'No se ha encontrado el miembro de gobierno.','status' => 422], 200);
            }
        }

        if($request->accion!='delete'){
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
                            return response()->json(['errors' => 'No se pudo añadir el miembro de gobierno: ya existe un Director/a | Decano/a vigente en el centro seleccionado', 'status' => 422], 200);
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
                            return response()->json(['errors' => 'No se pudo añadir el miembro de gobierno: ya existe un Secretario/a vigente en el centro seleccionado', 'status' => 422], 200);
                    }

                    // Comprobación existencia usuario en el centro
                        $usuarioEnCentro = MiembroGobierno::select('id')
                            ->where('idCentro', $request->data['idCentro'])
                            ->where('idUsuario', $request->data['idUsuario'])
                            ->where('fechaCese', null)
                            ->whereNot('id', $request->id)
                            ->first();
        
                        if($usuarioEnCentro)
                            return response()->json(['errors' => 'No se pudo añadir el miembro de gobierno: ya existe el usuario vigente en el centro seleccionado', 'status' => 422], 200);
            
                        if($request->accion=='update'){
                            // Comprobación existencia centro vigente
                            $centroVigenteMiembro = Centro::where('id', $request->data['idCentro'])
                            ->whereNotNull('deleted_at')
                            ->first();
        
                            if($centroVigenteMiembro)
                                return response()->json(['errors' => 'No se pudo actualizar el miembro de gobierno: el centro no está vigente', 'status' => 422], 200);
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
