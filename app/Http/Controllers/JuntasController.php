<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Junta;
use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de las juntas
 * 
 * @author Javier Ruiz Jurado
 */
class JuntasController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve las juntas según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view juntas.blade.php con las juntas y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener las juntas
     */
    public function index(Request $request)
    {
        try {
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion', 'updated_at', 'deleted_at');
            $centros = Centro::select('id', 'nombre');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $juntas = $juntas->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                $centros = $centros->where('id', $datosResponsableCentro['idCentros']);
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $juntas = $juntas->whereIn('id', $datosResponsableJunta['idJuntas']);
                $centros = $centros->whereIn('id', $datosResponsableJunta['idCentros']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $juntas = $juntas->withTrashed()->filters($request);
                    break;
                default:
                    $juntas = $juntas->whereNull('deleted_at');
                    break;
            }

            $centros=$centros->get();
            $juntas = $juntas
            ->orderBy('deleted_at')
            ->orderBy('updated_at','desc')
            ->orderBy('fechaDisolucion')
            ->orderBy('fechaConstitucion', 'desc')
            ->paginate(12);

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
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener las juntas.']);
        }
    }

     /**
     * @brief Método encargado de guardar una junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la junta se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar la junta
     */
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
            ]);

            return response()->json(['message' => "La junta de centro '{$junta->centro->nombre}' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir la junta de centro", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar una junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la junta se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar la junta
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateJunta($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $junta=Junta::where('id', $request->id)->first();

            if($request->data['fechaDisolucion']!=null){
                $miembrosJunta = DB::table('miembros_junta')
                ->where('idJunta', $junta->id)
                ->update(['fechaCese' => $request->data['fechaDisolucion']]);
            }

            $junta->fechaConstitucion = $request->data['fechaConstitucion'];
            $junta->fechaDisolucion = $request->data['fechaDisolucion'];
            $junta->save();

            return response()->json(['message' => "La junta de centro '{$junta->centro->nombre}' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar la junta de centro '{$junta->centro->nombre}'", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar una junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la junta se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar la junta
     */
    public function delete(Request $request)
    {
        try {
            $request['accion']='delete';
            $validation = $this->validateJunta($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $junta = Junta::where('id', $request->id)->first();
            $junta->delete();

            return response()->json(['message' => "La junta de centro '{$junta->centro->nombre}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar la junta de centro",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener una junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos de la junta a obtener
     * @throws \Throwable Si no se pudo obtener la junta, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $junta = Junta::withTrashed()->where('id', $request->id)->first();
            if (!$junta) {
                throw new Exception();
            }

            return response()->json($junta);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 500], 200);
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
            'fechaDisolucion.date' => 'La fecha de disolución debe tener el formato fecha DD/MM/YYYY.',
        ];

        return [$rules, $rules_message];
    }

    /**
     * @brief Método encargado de validar los datos de una junta, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la junta se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación, o la fecha de disolución es menor que la fecha de constitución, o ya existe una junta vigente para el centro indicado
     * UPDATE: No se ha encontrado la junta a actualizar o no ha pasado las reglas de validación, o la fecha de disolución es menor que la fecha de constitución, o ya existe una junta vigente para el centro indicado
     * DELETE: No se ha encontrado la junta a eliminar o existen comisiones asociadas a la junta o existen miembros de junta asociados al centro o existen convocatorias de junta asociadas a la junta.
     */
    public function validateJunta(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $junta = Junta::where('id', $request->id)->first();

            if (!$junta) 
                return response()->json(['errors' => 'No se ha encontrado la junta.','status' => 422], 200);
        }

        if($request->accion=='delete'){
            
            if($junta->miembros->count() > 0)
                return response()->json(['errors' => 'Existen miembros de junta asociados a esta junta. Para borrar la junta es necesario eliminar todos sus miembros de junta.', 'status' => 422], 200);

            if($junta->comisiones->count() > 0)
                return response()->json(['errors' => 'Existen comisiones asociadas a esta junta. Para borrar la junta es necesario eliminar todas sus comisiones.', 'status' => 422], 200);

            if($junta->convocatorias->count() > 0)
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
                                ->first();
                            break;
                        case 'update':
                            $junta = Junta::select('id')
                                ->where('idCentro', $request->data['idCentro'])
                                ->where('fechaDisolucion', null)
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
