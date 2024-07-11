<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Helpers\Helper;
use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
                ->orderBy('updated_at','desc')
                ->orderBy('idTipo')
                ->orderBy('nombre')
                ->paginate(10);

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
            return redirect()->route('centros')->with('errors', 'No se pudieron obtener los centros: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {                
        try {
            $validation = $this->validateCentro($request);
            if(isset($validation)){
                return $validation;
            }

            $url_image = Helper::subirImagenCloudinary(isset($request->data['logo'])?$request->data['logo']:null, "logosCentros");

            $centro = Centro::create([
                "nombre" => $request->data['nombre'],
                "direccion" => $request->data['direccion'],
                "idTipo" => $request->data['idTipo'],
                "logo" => $url_image,
            ]);

            return response()->json(['message' => 'El centro se ha añadido correctamente.', 'status' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al añadir el centro.', 'status' => 422], 200);
        }
    }

    public function delete(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();

            if (!$centro) 
                return response()->json(['errors' => 'No se ha encontrado el centro.','status' => 422], 200);

            if($centro->juntas->count() > 0)
                return response()->json(['errors' => 'Existen juntas asociadas a este centro. Para borrar el centro es necesario eliminar todas sus juntas.', 'status' => 422], 200);

            if($centro->miembros->count() > 0)
                return response()->json(['errors' => 'Existen miembros de gobierno asociados a este centro. Para borrar el centro es necesario eliminar todos sus miembros de gobierno.', 'status' => 422], 200);

            $centro->delete();
            return response()->json(['status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el centro.','status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $centro = DB::table('centros')
            ->join('tipos_centro', 'centros.idTipo', '=', 'tipos_centro.id')
            ->where('centros.id', $request->id)
            ->select('centros.id', 'centros.nombre', 'centros.direccion', 'centros.idTipo', 'centros.logo', 'tipos_centro.nombre as tipo', 'centros.deleted_at')
            ->first();

            if (!$centro) {
                return response()->json(['errors' => 'No se ha encontrado el centro.', 'status' => 422], 200);
            }
            return response()->json($centro);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el centro.', 'status' => 422], 200);
        }
    }

    public function update(Request $request)
    {
        try {

            $validation = $this->validateCentro($request);
            if(isset($validation)){
                return $validation;
            }

            $centro = Centro::where('id', $request->id)->first();
            if (!$centro) {
                return response()->json(['errors' => 'No se ha encontrado el centro.', 'status' => 422], 200);
            }

            if(isset($request->data['logo'])){
                $url_image = Helper::subirImagenCloudinary($request->data['logo'], "logosCentros");
                $centro->logo = $url_image;
            }

            $centro->nombre = $request->data['nombre'];
            $centro->direccion = $request->data['direccion'];
            $centro->idTipo = $request->data['idTipo'];
            $centro->save();
            return response()->json(['message' => 'El centro se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Error al actualizar el centro.', 'status' => 422], 200);
        }
    }

    public function all()
    {
        try {
            
            $centros = Centro::select('id', 'nombre')->get();

            if (!$centros) {
                return response()->json(['errors' => 'No se han podido obtener los centros.','status' => 422], 200);
            }

            return response()->json($centros);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener los centros.','status' => 422], 200);
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
        $validator = Validator::make($request->data, $this->rules()[0], $this->rules()[1]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first(), 'status' => 422], 200);
        }
        else{
            return null;
        }
    }
}
