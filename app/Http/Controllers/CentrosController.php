<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Helpers\Helper;
use App\Models\TipoCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CentrosController extends Controller
{
    public function index(Request $request)
    {

        switch ($request->input('action')) {
            case 'limpiar':
                $request['filtroTipo']="";
                $request['filtroNombre']="";
                break;
        }

        try {
            $centros = Centro::select('id', 'nombre', 'direccion', 'idTipo', 'logo', 'estado')
            ->filters($request)
            ->where('estado', 1)
            ->orderBy('idTipo')
            ->orderBy('nombre')
            ->paginate(10);

            $tiposCentro = TipoCentro::select('id', 'nombre')->get();

            return view('centros', [
                'centros' => $centros, 
                'tiposCentro' => $tiposCentro,
                'filtroTipo' => $request['filtroTipo'],
                'filtroNombre' => $request['filtroNombre'],
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudieron obtener los centros: ' . $th->getMessage());
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
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);

            return response()->json(['message' => 'El centro se ha añadido correctamente.', 'status' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al añadir el centro.', 'status' => 404], 404);
        }
    }

    public function delete(Request $request)
    {
        try {
            $centro = Centro::where('id', $request->id)->first();

            if (!$centro) 
                return response()->json(['error' => 'No se ha encontrado el centro.'], 404);

            if($centro->juntas->where('estado', 1)->count() > 0)
                return response()->json(['error' => 'Existen juntas asociadas a este centro. Para borrar el centro es necesario eliminar todas sus juntas.', 'status' => 404], 200);

            if($centro->miembros->where('estado', 1)->count() > 0)
                return response()->json(['error' => 'Existen miembros de gobierno asociados a este centro. Para borrar el centro es necesario eliminar todos sus miembros de gobierno.', 'status' => 404], 200);

            $centro->estado = 0;
            $centro->save();
            return response()->json(['status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
        }
    }

    public function get(Request $request)
    {
        try {
            $centro = DB::table('centros')
            ->join('tipos_centro', 'centros.idTipo', '=', 'tipos_centro.id')
            ->where('centros.id', $request->id)
            ->select('centros.id', 'centros.nombre', 'centros.direccion', 'centros.idTipo', 'centros.logo', 'centros.estado', 'tipos_centro.nombre as tipo')
            ->first();

            if (!$centro) {
                return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
            }
            return response()->json($centro);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el centro.'], 404);
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
                return response()->json(['error' => 'No se ha encontrado el centro.', 'status' => 404], 404);
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
            return response()->json(['error' => 'Error al actualizar el centro.', 'status' => 404], 404);
        }
    }

    public function all()
    {
        try {
            $centros = Centro::all()->where('estado',1);

            if (!$centros) {
                return response()->json(['error' => 'No se han podido obtener los centros.'], 404);
            }

            return response()->json($centros);
        } catch (\Throwable $th) {
            return redirect()->route('centros')->with('error', 'No se pudieron obtener los centros: ' . $th->getMessage());
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
