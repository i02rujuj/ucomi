<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Junta;
use App\Models\Centro;
use App\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComisionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $comisiones = Comision::select('id', 'nombre', 'descripcion', 'idJunta', 'fechaConstitucion', 'fechaDisolucion', 'updated_at', 'deleted_at');
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){

                $comisiones = $comisiones->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                }); 
                $juntas = $juntas->whereIn('idCentro', $datosResponsableCentro['idCentros']);
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $comisiones = $comisiones->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
                $juntas = $juntas->whereIn('juntas.id', $datosResponsableJunta['idJuntas']);
            }

            if($datosResponsableComision = Auth::user()->esResponsableDatos('comision')['comisiones']){
                $comisiones = $comisiones->whereIn('comisiones.id', $datosResponsableComision['idComisiones']);
                $juntas = $juntas->whereIn('juntas.id', $datosResponsableComision['idJuntas']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroCentro']=null;
                    $request['filtroJunta']=null;
                    $request['filtroVigente']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $comisiones = $comisiones->withTrashed()->filters($request);
                    break;
                default:
                    $comisiones = $comisiones->whereNull('deleted_at');
                    break;
            }

            $centros = Centro::select('id', 'nombre')->get();
            $juntas=$juntas->get();

            $comisiones = $comisiones
            ->orderBy('deleted_at')
            ->orderBy('updated_at','desc')
            ->orderBy('fechaDisolucion')
            ->orderBy('fechaConstitucion', 'desc')
            ->paginate(12);

            if($request->input('action')=='limpiar'){
                return redirect()->route('comisiones')->with([
                    'comisiones' => $comisiones, 
                    'juntas' => $juntas,
                    'centros' => $centros,
                ]);
            }

            return view('comisiones', [
                'comisiones' => $comisiones, 
                'juntas' => $juntas,
                'centros' => $centros,
                'filtroCentro' => $request['filtroCentro'],
                'filtroJunta' => $request['filtroJunta'],
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener las comisiones.']);
        }
    }

    public function store(Request $request)
    {
        try {
            $request['accion']='add';
            $validation = $this->validateComision($request);
            if($validation->original['status']!=200){
                return $validation;
            }
           
            $comision = Comision::create([
                "nombre" => $request->data['nombre'],
                "descripcion" => $request->data['descripcion'],
                "idJunta" => $request->data['idJunta'],
                "fechaConstitucion" => $request->data['fechaConstitucion'],
                "fechaDisolucion" => $request->data['fechaDisolucion'],
            ]);

            return response()->json(['message' => "La comisión '{$comision->nombre}' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir la comisión'", 'status' => 500], 200);
        }
    }

    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateComision($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $comision=Comision::where('id', $request->id)->first();

            if($request->data['fechaDisolucion']!=null){
                DB::table('miembros_comision')
                ->where('idComision', $comision->id)
                ->update(['fechaCese' => $request->data['fechaDisolucion']]);
            }

            $comision->nombre = $request->data['nombre'];
            $comision->descripcion = $request->data['descripcion'];
            $comision->fechaConstitucion = $request->data['fechaConstitucion'];
            $comision->fechaDisolucion = $request->data['fechaDisolucion'];
            $comision->save();

            return response()->json(['message' => "La comision '{$comision->nombre}' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar la comisión '{$comision->nombre}'", 'status' => 500], 200);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request['accion']='delete';
            $validation = $this->validateComision($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $comision = Comision::where('id', $request->id)->first();
            $comision->delete();

            return response()->json(['message' => "La comisión '{$comision->nombre}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar la comisión",'status' => 500], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $comision = Comision::withTrashed()->where('id', $request->id)->first();
            if (!$comision) {
                throw new Exception();
            }
            
            return response()->json($comision);
        } catch (\Throwable $th) {
            return response()->json(['errors' => "No se ha encontrado la comisión.",'status' => 500], 200);
        }
    }

    public function rules()
    {
        $rules = [
            'nombre' => 'required|max:100|string',
            'descripcion' => 'nullable|string|max:250',
            'idJunta' => 'required|integer|exists:App\Models\Junta,id',
            'fechaConstitucion' => 'required|date',
            'fechaDisolucion' => 'nullable|date',
        ]; 
        
        $rules_message = [
            // Mensajes error nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre no puede contener números ni caracteres especiales.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            // Mensajes error descripcion
            'descripcion.string' => 'La descripcion debe ser una cadena de texto.',
            'descripcion.max' => 'La descripcion no puede exceder los 250 carácteres.',
            // Mensajes error idJunta
            'idJunta.required' => 'La junta es obligatoria.',
            'idJunta.integer' => 'La junta debe ser un entero.',
            'idJunta.exists' => 'La junta seleccionado no existe.',
            // Mensajes error fechaConstitucion
            'fechaConstitucion.required' => 'La fecha de constitución es obligatoria.',
            'fechaConstitucion.date' => 'La fecha de constitución debe tener el formato fecha DD/MM/YYYY.',
            // Mensajes error fechaDisolucion
            'fechaDisolucion.date' => 'La fecha de disolución debe tener el formato fecha DD/MM/YYYY.',
        ];

        return [$rules, $rules_message];
    }

    public function validateComision(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $comision = Comision::where('id', $request->id)->first();

            if (!$comision) {
                return response()->json(['errors' => 'No se ha encontrado la comision.','status' => 422], 200);
            }
        }

        if($request->accion=='delete'){
            if($comision->miembros->count() > 0)
                return response()->json(['errors' => 'Existen miembros de comision asociadas a esta comision. Para borrar la comision es necesario eliminar todos sus miembros de comision.', 'status' => 422], 200);

            if($comision->convocatorias->count() > 0)
                return response()->json(['errors' => 'Existen convocatorias asociadas a esta comision. Para borrar la comision es necesario eliminar todas sus comision.', 'status' => 422], 200);
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
            }   
        }
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }
}
