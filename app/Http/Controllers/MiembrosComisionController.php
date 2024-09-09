<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\User;
use App\Models\Junta;
use App\Models\Centro;
use App\Models\Comision;
use Illuminate\Http\Request;
use App\Models\Representacion;
use App\Models\MiembroComision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de los miembros de comisión
 * 
 * @author Javier Ruiz Jurado
 */
class MiembrosComisionController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve los miembros de comisión según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view miembrosComision.blade.php con los miembros y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener los miembros
     */
    public function index(Request $request)
    {
        try {
            $miembrosComision = MiembroComision::select('id', 'idComision', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
            $comisiones = Comision::select('id', 'nombre', 'descripcion', 'idJunta', 'fechaConstitucion', 'fechaConstitucion');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $miembrosComision = $miembrosComision
                ->whereHas('comision', function($builder) use ($datosResponsableCentro){
                    return $builder
                    ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                        $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                    });
                }); 

                $comisiones = $comisiones
                ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                });          
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $miembrosComision = $miembrosComision
                ->whereHas('comision', function($builder) use ($datosResponsableJunta){
                    return $builder->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
                }); 

                $comisiones = $comisiones->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
            }

            if($datosResponsableComision = Auth::user()->esResponsableDatos('comision')['comisiones']){
                $miembrosComision = $miembrosComision->whereIn('idComision', $datosResponsableComision['idComisiones']);
                $comisiones = $comisiones->whereIn('id', $datosResponsableComision['idComisiones']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroJunta']=null;
                    $request['filtroComision']=null;
                    $request['filtroRepresentacion']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $miembrosComision = $miembrosComision->withTrashed()->filters($request);
                    break;
                default:
                    $miembrosComision = $miembrosComision->whereNull('deleted_at');
                    break;
            }

            $comisiones = $comisiones->get();
            
            $miembrosComision = $miembrosComision
            ->orderBy('deleted_at')
            ->orderBy('updated_at','desc')
            ->orderBy('fechaCese')
            ->orderBy('idComision')
            ->orderBy('idRepresentacion')
            ->orderBy('fechaTomaPosesion', 'desc')
            ->orderBy('idUsuario')
            ->paginate(12);

            $users = User::select('id', 'name')
            ->get();
            
            $representacionesGeneral = Representacion::select('id', 'nombre')
            ->where('deComision', 1)
            ->get();   
            $centros = Centro::select('id', 'nombre')->get();
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion')->get(); 
            
            if($request->input('action')=='limpiar'){
                return redirect()->route('miembrosComision')->with([
                    'miembrosComision' => $miembrosComision,
                    'comisiones' => $comisiones, 
                    'users' => $users, 
                    'representacionesGeneral' => $representacionesGeneral, 
                    'centros' => $centros, 
                    'juntas' => $juntas,
                ]);
            }

            return view('miembrosComision', [
                'miembrosComision' => $miembrosComision,
                'comisiones' => $comisiones, 
                'users' => $users, 
                'representacionesGeneral' => $representacionesGeneral, 
                'centros' => $centros, 
                'juntas' => $juntas, 
                'filtroCentro' => $request['filtroCentro'],
                'filtroJunta' => $request['filtroJunta'],
                'filtroComision' => $request['filtroComision'],
                'filtroRepresentacion' => $request['filtroRepresentacion'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);
        
        } catch (\Throwable $th) {
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener los miembros de comisión.']);
        }
    }

    /**
     * @brief Método encargado de guardar un miembro de comisión si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de comisión se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar el miembro de comisión
     */
    public function store(Request $request)
    {
        try {
            $request['accion']='add';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            } 

            $miembroComision = MiembroComision::create([
                "idComision" => $request->data['idComision'],
                "idUsuario" => $request->data['idUsuario'],
                "fechaTomaPosesion" => $request->data['fechaTomaPosesion'],
                "fechaCese" => $request->data['fechaCese'],
                "idRepresentacion" => $request->data['idRepresentacion'],
                "cargo" => $request->data['cargo'],
                "responsable" => $request->data['responsable'],
            ]);

            return response()->json(['message' => "El miembro de comisión '{$miembroComision->usuario->name}' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir el miembro de comisión", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar un miembro de comisión si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de comisión se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar el miembro de comisión
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateMiembro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $miembroComision = MiembroComision::where('id', $request->id)->first();

            $miembroComision->idRepresentacion = $request->data['idRepresentacion'];
            $miembroComision->cargo = $request->data['cargo'];
            $miembroComision->fechaTomaPosesion = $request->data['fechaTomaPosesion'];
            $miembroComision->fechaCese = $request->data['fechaCese'];  
            $miembroComision->responsable = $request->data['responsable'];  
            $miembroComision->save();

            return response()->json(['message' => "El miembro de comision '{$miembroComision->usuario->name}' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar el miembro de comisión '{$miembroComision->usuario->name}'", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar un miembro de comisión si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de comisión se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar el miembro de comisión
     */
    public function delete(Request $request)
    {
        $request['accion']='delete';
        $validation = $this->validateMiembro($request);
        if($validation->original['status']!=200){
            return $validation;
        }

        try {
            $miembroComision = MiembroComision::where('id', $request->id)->first();
            $miembroComision->delete();

            return response()->json(['message' => "El miembro de comisión '{$miembroComision->usuario->name}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar el miembro de comisión",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener un miembro de comisión si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos del miembro de comisión a obtener
     * @throws \Throwable Si no se pudo obtener el miembro de comisión, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $miembroComision = MiembroComision::withTrashed()->where('id', $request->id)->first();
            if (!$miembroComision) {
                throw new Exception();
            }
            
            return response()->json($miembroComision);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el miembro de comisión.','status' => 500], 200);
        }
    }

    /**
     * @brief Método que establece las reglas de validación, así como los mensajes que serán devueltos en caso de no pasar la validación
     * @return array con las reglas y mensajes de validación
     */
    public function rules(){
        $rules = [
            'idComision' => 'required|integer|exists:App\Models\Comision,id',
            'idUsuario' => 'required|integer|exists:App\Models\User,id',
            'idRepresentacion' => 'required|integer|exists:App\Models\Representacion,id',
            'cargo' => 'nullable|max:100|string',
            'fechaTomaPosesion' => 'required|date',
            'fechaCese' => 'nullable|date',
            'idRepresentacion' => 'required|integer|exists:App\Models\Representacion,id',
        ];

        $rules_message = [
            // Mensajes error idComision
            'idComision.required' => 'La comisión es obligatoria.',
            'idComision.integer' => 'La comisión debe ser un entero.',
            'idComision.exists' => 'La comisión seleccionada no existe.',
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
        ];

        return [$rules, $rules_message];
    }

    /**
     * @brief Método encargado de validar los datos de un miembro de comisión, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el miembro de comisión se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe el usuario como miembro de la comisión, o ya existe un presidente en la comisión
     * UPDATE: No se ha encontrado el miembro a actualizar o no ha pasado las reglas de validación, o la fecha de cese es menor que la fecha de toma de posesión, o ya existe un presidente en la comisión
     * DELETE: No se ha encontrado el miembro a eliminar.
     */
    public function validateMiembro(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $miembroComision = MiembroComision::where('id', $request->id)->first();

            if (!$miembroComision) {
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

                    // Comprobación existencia usuario en la comisión
                    $usuarioEnComision = MiembroComision::select('id')
                        ->where('idComision', $request->data['idComision'])
                        ->where('idUsuario', $request->data['idUsuario'])
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();

                    if($usuarioEnComision)
                        return response()->json(['errors' => 'No se pudo guardar el miembro de comisión: ya existe el usuario vigente en la comisión seleccionada', 'status' => 422], 200);
                
                    // Comprobación existencia presidente en la comisión
                    if($request->data['cargo'] == "Presidente"){
                        $presidenteEnComision = MiembroComision::select('id')
                        ->where('idComision', $request->data['idComision'])
                        ->where('cargo', 'Presidente')
                        ->where('fechaCese', null)
                        ->whereNot('id', $request->id)
                        ->first();

                        if($presidenteEnComision)
                            return response()->json(['errors' => 'No se pudo guardar el miembro de comisión: ya existe un presidente en la comisión seleccionada', 'status' => 422], 200);
                    }

                    if($request->accion=='update'){
                        // Comprobación existencia comisión vigente
                        $comisionVigenteMiembro = Comision::where('id', $request->data['idComision'])
                        ->whereNotNull('fechaDisolucion')
                        ->first();

                        if($comisionVigenteMiembro)
                            return response()->json(['errors' => 'No se pudo actualizar el miembro de comisión: la comisión no está vigente', 'status' => 422], 200);
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
