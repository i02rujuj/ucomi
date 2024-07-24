<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Centro;
use App\Helpers\Helper;
use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\Notification\NotificationInterface;

class CentrosController extends Controller
{
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
            toastr("No se pudieron obtener los centros.", NotificationInterface::ERROR, ' ');
            return redirect()->route('home')->with('errors', 'No se pudieron obtener los centros.');
        }
    }

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

            toastr("El centro '$centro->nombre' se ha añadido correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "El centro '$centro->nombre' se ha añadido correctamente.", 'status' => 200], 200);
        } catch (\Throwable $th) {
            toastr("Error al añadir el centro '$centro->nombre'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al añadir el centro '$centro->nombre'", 'status' => 422], 200);
        }
    }

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

           // toastr("El centro '$centro->nombre' se ha actualizado correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "El centro '$centro->nombre' se ha actualizado correctamente.", 'status' => 200], 200);
        } catch (\Throwable $th) {
            toastr("Error al actualizar el centro '$centro->nombre'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al actualizar el centro '$centro->nombre'", 'status' => 422], 200);
        }
    }

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

            toastr("El centro '$centro->nombre' se ha eliminado correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "El centro '$centro->nombre' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            toastr("Error al eliminar el centro '$centro->nombre'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al eliminar el centro '$centro->nombre'",'status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();

            if (!$centro) {
                throw new Exception();
            }

            return response()->json($centro);
        } catch (\Throwable $th) {
            toastr("No se ha encontrado el centro.", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => 'No se ha encontrado el centro.', 'status' => 422], 200);
        }
    }

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
