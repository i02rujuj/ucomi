<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Junta;
use App\Models\Comision;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComisionController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if($user->hasRole('admin')){
                $comisiones = Comision::select('id', 'idJunta', 'nombre', 'descripcion', 'fechaConstitucion', 'fechaDisolucion', 'estado')
                ->where('estado', 1)
                ->orderBy('fechaDisolucion')          
                ->orderBy('idJunta')
                ->orderBy('fechaConstitucion')
                ->get();
                
                $juntas = Junta::where('estado', 1)
                ->where('fechaDisolucion', null)
                ->get();
            }

            if($user->hasRole('responsable_centro')){

                $centroResponsable = MiembroGobierno::where('idUsuario', $user->id)
                ->select('idCentro')
                ->first();

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->join('juntas', 'juntas.id', '=', 'comisiones.idJunta')
                ->where('juntas.idCentro', $centroResponsable->idCentro)
                ->where('juntas.estado', 1)
                ->orderBy('comisiones.fechaDisolucion')          
                ->orderBy('comisiones.idJunta')
                ->orderBy('comisiones.fechaConstitucion')
                ->get();
                
                $juntas = Junta::where('estado', 1)
                ->where('idCentro', $centroResponsable->idCentro)
                ->where('fechaDisolucion', null)
                ->get();
            }

            if($user->hasRole('responsable_junta')){
                $juntaResponsable = MiembroJunta::where('idUsuario', $user->id)
                ->select('idJunta')
                ->first();

                $comisiones = Comision::select('comisiones.*')
                ->where('comisiones.estado', 1)
                ->where('idJunta', $juntaResponsable->idJunta)
                ->orderBy('comisiones.fechaDisolucion')          
                ->orderBy('comisiones.idJunta')
                ->orderBy('comisiones.fechaConstitucion')
                ->get();
                
                $juntas = Junta::where('estado', 1)
                ->where('id', $juntaResponsable->idJunta)
                ->where('fechaDisolucion', null)
                ->get();
            }


            return view('comisiones', ['comisiones' => $comisiones, 'juntas' => $juntas]);
        } catch (\Throwable $th) {
            return redirect()->route('comisiones')->with('error', 'No se pudieron obtener las comisiones: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'idJunta' => 'required|integer|exists:App\Models\Junta,id',
                'nombre' => 'required|max:100|string',
                'descripcion' => 'nullable|string|max:250',
                'fechaConstitucion' => 'required|date',
                'fechaDisolucion' => 'nullable|date',
            ], [
                // Mensajes error idJunta
                'idJunta.required' => 'La junta es obligatoria.',
                'idJunta.integer' => 'La junta debe ser un entero.',
                'idJunta.exists' => 'La junta seleccionado no existe.',
                // Mensajes error nombre
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre no puede contener números ni caracteres especiales.',
                'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
                // Mensajes error descripcion
                'descripcion.string' => 'La descripcion debe ser una cadena de texto.',
                'descripcion.max' => 'La descripcion no puede exceder los 250 carácteres.',
                // Mensajes error fechaConstitucion
                'fechaConstitucion.required' => 'La fecha de constitución es obligatoria.',
                'fechaConstitucion.date' => 'La fecha de constitución debe tener el formato fecha DD/MM/YYYY.',
                // Mensajes error fechaDisolucion
                'fechaDisolucion.date' => 'La fecha de cese debe tener el formato fecha DD/MM/YYYY.',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if($request->fechaDisolucion != null){
                // Validar que fechaTomaPosesión no pueda ser mayor a fechaCese
                $dateConstitucion = new DateTime($request->fechaConstitucion);
                $dateDisolucion = new DateTime($request->fechaDisolucion);

                if ($dateConstitucion>$dateDisolucion) {
                    return redirect()->route('comisiones')->with('error', 'La fecha de disolución no puede ser anterior a la fecha de constitución')->withInput();
                }
            }

            $comision = Comision::create([
                "idJunta" => $request->idJunta,
                "nombre" => $request->nombre,
                "descripcion" => $request->descripcion,
                "fechaConstitucion" => $request->fechaConstitucion,
                "fechaDisolucion" => $request->fechaDisolucion,
                'estado' => 1, // 1 = 'Activo' | 0 = 'Inactivo'
            ]);
            return redirect()->route('comisiones')->with('success', 'Comisión creada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('comisiones')->with('error', 'No se pudo crear la comisión: ' . $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $comision = Comision::where('id', $request->id)->first();

            if (!$comision) {
                return response()->json(['error' => 'No se ha encontrado el comisión.'], 404);
            }

            $comision->estado = 0;
            $comision->save();
            return response()->json($request);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el comisión.'], 404);
        }
    }

    public function get(Request $request)
    {
        try {
            $comision = Comision::where('id', $request->id)->first();
            if (!$comision) {
                return response()->json(['error' => 'No se ha encontrado la comisión.'], 404);
            }
            return response()->json($comision);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado la comisión.'], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $comision = Comision::where('id', $request->id)->first();
            if (!$comision) {
                return response()->json(['error' => 'No se ha encontrado el comisión.', 'status' => 404], 404);
            }

            // Validar que fechaConstitución no pueda ser mayor a fechaDisolución
            $dateConstitucion = new DateTime($request->data['fechaConstitucion']);
            $dateDisolucion = new DateTime($request->data['fechaDisolucion']);

            if ($dateConstitucion>$dateDisolucion) {
                return response()->json(['error' => 'La fecha de disolución no puede ser anterior a la fecha de constitución de la comisión', 'status' => 404], 200);
            } 
            
            $comision->nombre = $request->data['nombre'];
            $comision->descripcion = $request->data['descripcion'];
            $comision->idJunta = $request->data['idJunta'];
            $comision->fechaConstitucion = $request->data['fechaConstitucion'];
            $comision->fechaDisolucion = $request->data['fechaDisolucion'];
            $comision->save();
            return response()->json(['message' => 'La comisión se ha actualizado correctamente.', 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al actualizar la comisión.', 'status' => 404], 404);
        }
    }
}
