<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Centro;
use App\Helpers\Helper;
use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de los centros
 * 
 * @author Javier Ruiz Jurado
 */
class CentrosController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve los centros según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view centros.blade.php con los centros y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener los centros
     */
    public function index(Request $request)
    {
        try {
            $centros = Centro::select('id', 'nombre', 'direccion', 'idTipo', 'logo', 'updated_at', 'deleted_at');
            
            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $centros = $centros
                ->whereIn('id', $datosResponsableCentro['idCentros']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroNombre']=null;
                    $request['filtroTipo']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $centros = $centros->withTrashed()->filters($request);
                    break;
                default:
                    $centros = $centros->whereNull('deleted_at');
                    break;
            }     

            $centros=$centros
                ->orderBy('deleted_at')
                ->orderBy('updated_at','desc')
                ->orderBy('idTipo')
                ->orderBy('nombre')
                ->paginate(12);

            $tiposCentro = TipoCentro::select('id', 'nombre')->get();

            if($request->input('action')=='limpiar'){
                return redirect()->route('centros')->with([
                    'centros' => $centros, 
                    'tiposCentro' => $tiposCentro,
                ]);
            }
            
            return view('centros', [
                'centros' => $centros, 
                'tiposCentro' => $tiposCentro,
                'filtroNombre' => $request['filtroNombre'],
                'filtroTipo' => $request['filtroTipo'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('home')->with(['errors' => 'No se pudieron obtener los centros.']);
        }
    }

     /**
     * @brief Método encargado de guardar un centro si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el centro se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar el centro
     */
    public function store(Request $request)
    {                
        try {
            $request['accion']='store';
            $validation = $this->validateCentro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $url_image = Helper::subirImagenCloudinary(isset($request->data['logo'])?$request->data['logo']:null, "logosCentros");

            $centro = Centro::create([
                "nombre" => $request->data['nombre'],
                "direccion" => $request->data['direccion'],
                "idTipo" => $request->data['idTipo'],
                "logo" => $url_image,
            ]);

            return response()->json(['message' => "El centro '$centro->nombre' se ha añadido correctamente.", 'status' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir el centro", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar un centro si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el centro se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar el centro
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateCentro($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $centro = Centro::where('id', $request->id)->first();

            if(isset($request->data['logo'])){
                $url_image = Helper::subirImagenCloudinary($request->data['logo'], "logosCentros");
                $centro->logo = $url_image;
            }

            $centro->nombre = $request->data['nombre'];
            $centro->direccion = $request->data['direccion'];
            $centro->idTipo = $request->data['idTipo'];
            $centro->save();

            return response()->json(['message' => "El centro '$centro->nombre' se ha actualizado correctamente.", 'status' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar el centro", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar un centro si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el centro se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar el centro
     */
    public function delete(Request $request)
    {
        try {
            $request['accion']='delete';
            $validation = $this->validateCentro($request);
            if($validation->original['status']!=200){
                return $validation;
            } 

            $centro = Centro::where('id', $request->id)->first();
            $centro->delete();

            return response()->json(['message' => "El centro '$centro->nombre' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar el centro",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener un centro si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos del centro a obtener
     * @throws \Throwable Si no se pudo obtener el centro, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();

            if (!$centro) {
                throw new Exception();
            }

            return response()->json($centro);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el centro.', 'status' => 500], 200);
        }
    }

    /**
     * @brief Método que establece las reglas de validación, así como los mensajes que serán devueltos en caso de no pasar la validación
     * @return array con las reglas y mensajes de validación
     */
    public function rules()
    {
        $rules = [
            'nombre' => 'required|max:100|string',
            'direccion' => 'required|string|max:150',
            'idTipo' => 'required|integer|exists:App\Models\TipoCentro,id',
            'logo' => 'nullable|max:1000|mimes:png,jpg,jpeg',
        ]; 
        
        $rules_message = [
            // Mensajes error nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre no puede contener números ni caracteres especiales.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            // Mensajes error dirección
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede exceder los 150 carácteres.',
            // Mensajes error tipo
            'idTipo.required' => 'El tipo es obligatorio.',
            'idTipo.integer' => 'El tipo debe ser un entero',
            'idTipo.exixts' => 'El tipo no existe.',
             // Mensajes error logo
             'logo.mimes' => 'El logo debe estar en formato jpeg, jpg ó png.',
             'logo.max' => 'El nombre del logo no puede exceder los 1000 caracteres.',
        ];

        return [$rules, $rules_message];
    }

    /**
     * @brief Método encargado de validar los datos de un centro, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que el centro se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación
     * UPDATE: No se ha encontrado el centro a actualizar o no ha pasado las reglas de validación
     * DELETE: No se ha encontrado el centro a eliminar o existen juntas asociadas al centro o existen miembros de gobierno asociados al centro.
     */
    public function validateCentro(Request $request){
        
        if($request->accion=='update' || $request->accion=='delete'){
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro)
                return response()->json(['errors' => 'No se ha encontrado el centro.','status' => 422], 200);
        }
        
        if($request->accion=='delete'){
            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) 
                return response()->json(['errors' => 'No se ha encontrado el centro.','status' => 422], 200);

            if($centro->juntas->count() > 0)
                return response()->json(['errors' => 'Existen juntas asociadas a este centro. Para borrar el centro es necesario eliminar todas sus juntas.', 'status' => 422], 200);

            if($centro->miembros->count() > 0)
                return response()->json(['errors' => 'Existen miembros de gobierno asociados a este centro. Para borrar el centro es necesario eliminar todos sus miembros de gobierno.', 'status' => 422], 200);
        }
        else{
            $validator = Validator::make($request->data, $this->rules()[0], $this->rules()[1]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first(), 'status' => 422], 200);
            }
        }
        
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }
}
