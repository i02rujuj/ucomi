<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Junta;
use App\Helpers\Helper;
use App\Models\Convocado;
use App\Models\Convocatoria;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\TipoConvocatoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificarConvocadosEmail;
use Illuminate\Support\Facades\Validator;

/**
 * @brief Clase que contiene la lógica de negocio para la gestión de las convocatorias de junta
 * 
 * @author Javier Ruiz Jurado
 */

class ConvocatoriasJuntaController extends Controller
{
    /**
     * @brief Método principal que obtiene, filtra, ordena y devuelve las convocatorias de junta según el tipo de usuario, paginados en bloques de doce elementos.
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return view convocatoriasComision.blade.php con las convocatorias y filtros aplicados
     * @throws \Throwable Si no se pudieron obtener las convocatorias de junta
     */
    public function index(Request $request)
    {
        try {
            $convocatorias = Convocatoria::select('id', 'idJunta', 'idTipo', 'lugar', 'fecha', 'hora', 'acta', 'updated_at', 'convocatorias.deleted_at');
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion', 'updated_at', 'deleted_at');

            if(Auth::user()->esResponsable('admin|centro|junta|comision')){

                if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                    $convocatorias = $convocatorias
                    ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                        return $builder
                        ->whereHas('centro', function($builder) use ($datosResponsableCentro){
                            $builder->whereIn('id', $datosResponsableCentro['idCentros']);
                        });
                    }); 
                    
                    $juntas = $juntas
                    ->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                }

                if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                    $convocatorias = $convocatorias->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
                    $juntas = $juntas->whereIn('id', $datosResponsableJunta['idJuntas']);
                }
            }
            else{

                if($datosMiembroCentro = Auth::user()->esMiembroDatos('centro')['centros']){
                    $convocatorias = $convocatorias
                    ->whereHas('junta', function($builder) use ($datosMiembroCentro){
                        return $builder
                        ->whereHas('centro', function($builder) use ($datosMiembroCentro){
                            $builder->whereIn('idCentro', $datosMiembroCentro['idCentros']);
                        });
                    }); 
                    
                    $juntas = $juntas
                    ->whereIn('idCentro', $datosMiembroCentro['idCentros']);
                }
    
                if($datosMiembroJunta = Auth::user()->esMiembroDatos('junta')['juntas']){
                    $convocatorias = $convocatorias->whereIn('idJunta', $datosMiembroJunta['idJuntas']);
                    $juntas = $juntas->whereIn('id', $datosMiembroJunta['idJuntas']);
                }      
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroJunta']=null;
                    $request['filtroTipo']=null;
                    $request['filtroEstado']=null;
                    break;
                case 'filtrar':
                    $convocatorias = $convocatorias->withTrashed()->filters($request);
                    break;
                default:
                    $convocatorias = $convocatorias->whereNull('deleted_at');
                    break;
            }

            $tipos = TipoConvocatoria::get();
            $juntas=$juntas->get();
            $convocatorias = $convocatorias
            ->whereNot('idJunta', null)
            ->orderBy('deleted_at')
            ->orderBy('updated_at','desc')
            ->orderBy('fecha', 'desc')  
            ->orderBy('hora', 'desc')          
            ->orderBy('idJunta')
            ->orderBy('idTipo')
            ->paginate(12);

            if($request->input('action')=='limpiar'){
                return redirect()->route('convocatorias')->with([
                    'convocatorias' => $convocatorias,
                    'juntas' => $juntas, 
                    'tipos' => $tipos, 
                ]);
            }

            return view('convocatoriasJunta', [
                'convocatorias' => $convocatorias,
                'juntas' => $juntas, 
                'tipos' => $tipos, 
                'filtroJunta' => $request['filtroJunta'],
                'filtroTipo' => $request['filtroTipo'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);

        } catch (\Throwable $th) {
            return redirect()->route('home')->with(['errors', 'No se pudieron obtener las convocatorias.']);
        }
    }
    
    /**
     * @brief Método encargado de guardar una convocatoria de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la convocatoria de junta se ha guardado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo guardar la convocatoria de junta
     */
    public function store(Request $request)
    {
        try {
            $request['accion']='add';
            $validation = $this->validateConvocatoria($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            if(isset($request->data['acta'])){
                $url_acta = Helper::subirPDFCloudinary($request->data['acta'], "actasJuntas");
            }

            $convocatoria = Convocatoria::create([
                "idJunta" => $request->data['idJunta'],
                "idTipo" => $request->data['idTipo'],
                "lugar" => $request->data['lugar'],
                "fecha" => $request->data['fecha'],
                "hora" => $request->data['hora'],
                "acta" => isset($url_acta) ? $url_acta : null,
            ]);

            $miembrosJunta = MiembroJunta::
            where('idJunta', $convocatoria->idJunta)
            ->get();

            foreach ($miembrosJunta as $miembro) {
                Convocado::create([
                    "idConvocatoria" => $convocatoria->id,
                    "idUsuario" => $miembro->usuario->id,
                    "asiste" => 0,
                    "notificado" => 0,
                ]);
            }

            return response()->json(['message' => "La convocatoria  del día '$convocatoria->fecha' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al añadir la convocatoria del día '$convocatoria->fecha'", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de actualizar una convocatoria de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la convocatoria de junta se ha actualizado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo actualizar la convocatoria de junta
     */
    public function update(Request $request)
    {
        try {
            $request['accion']='update';
            $validation = $this->validateConvocatoria($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $convocatoria=Convocatoria::where('id', $request->id)->first();

            if(isset($request->data['acta'])){
                $url_acta = Helper::subirPDFCloudinary($request->data['acta'], "actasJuntas");
                $convocatoria->acta = $url_acta;
            }

            $convocatoria->idTipo = $request->data['idTipo'];
            $convocatoria->lugar = $request->data['lugar'];
            $convocatoria->fecha = $request->data['fecha'];
            $convocatoria->hora = $request->data['hora'];
            $convocatoria->save();
           
            return response()->json(['message' => "La convocatoria del día '$convocatoria->fecha' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al actualizar la convocatoria del día '$convocatoria->fecha'", 'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de eliminar una convocatoria de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la convocatoria de junta se ha eliminado correctamente o mensaje indicando que los datos no han pasado la validación de datos
     * @throws \Throwable Si no se pudo eliminar la convocatoria de junta
     */
    public function delete(Request $request)
    {
        try {

            $request['accion']='delete';
            $validation = $this->validateConvocatoria($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            $convocatoria = Convocatoria::where('id', $request->id)->first();

            Convocado::where('idConvocatoria', $request->id)->delete();
            $convocatoria->delete();

            return response()->json(['message' => "La convocatoria del día '{$convocatoria->fecha}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => "Error al eliminar la convocatoria",'status' => 500], 200);
        }
    }

    /**
     * @brief Método encargado de obtener una convocatoria de junta si los datos de entrada son validados correctamente
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos de la convocatoria de junta a obtener
     * @throws \Throwable Si no se pudo obtener la convocatoria de junta, por ejemplo si no existe en la base de datos
     */
    public function get(Request $request)
    {
        try {
            $convocatoria = Convocatoria::withTrashed()->where('id', $request->id)->first();
            if (!$convocatoria) {
                throw new Exception();
            }

            return response()->json($convocatoria);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado la convocatoria.','status' => 500], 200);
        }
    }

    /**
     * @brief Método que establece las reglas de validación, así como los mensajes que serán devueltos en caso de no pasar la validación
     * @return array con las reglas y mensajes de validación
     */
    public function rules()
    {
        $rules = [
            'idJunta' => 'required|integer|exists:App\Models\Junta,id',
            'idTipo' => 'required|integer|exists:App\Models\TipoConvocatoria,id',
            'lugar' => 'required|max:100|string',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'acta' => 'nullable|max:1000|mimes:pdf',
        ]; 
        
        $rules_message = [
            // Mensajes error idJunta
            'idJunta.required' => 'La junta es obligatoria.',
            'idJunta.integer' => 'La junta debe ser un entero.',
            'idJunta.exists' => 'La junta seleccionada no existe.',
            // Mensajes error idTipo
            'idTipo.integer' => 'El tipo de convocatoria debe ser un entero.',
            'idTipo.exists' => 'El tipo de convocatoria seleccionado no existe.',
            // Mensajes error lugar
            'lugar.required' => 'El lugar es obligatorio.',
            'lugar.string' => 'El lugar no puede contener números ni caracteres especiales.',
            'lugar.max' => 'El lugar no puede exceder los 100 caracteres.',
            // Mensajes error fecha
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe tener el formato fecha DD/MM/YYYY.',
            // Mensajes error hora
            'hora.required' => 'La hora es obligatoria.',
            'hora.date_format' => 'La hora debe tener el formato hora HH:MM.',
            // Mensajes error acta
            'acta.mimes' => 'El acta debe estar en formato pdf.',
            'acta.max' => 'El nombre del acta no puede exceder los 1000 caracteres.',
        ];

        return [$rules, $rules_message];
    }

    /**
     * @brief Método encargado de validar los datos de una convocatoria de junta, tanto al guardar, actualizar o eliminar
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado indicando al usuario que la convocatoria de junta se ha validado correctamente o mensaje indicando que los datos no han pasado la validación de datos por diferentes motivos:
     * STORE: No ha pasado las reglas de validación
     * UPDATE: No se ha encontrado la convocatoria de junta a actualizar o no ha pasado las reglas de validación
     * DELETE: No se ha encontrado la convocatoria de junta a eliminar.
     */
    public function validateConvocatoria(Request $request){

        if($request->accion=='update' || $request->accion=='delete'){
            $convocatoria = Convocatoria::where('id', $request->id)->first();

            if (!$convocatoria) {
                return response()->json(['errors' => 'No se ha encontrado la convocatoria.','status' => 422], 200);
            }
        }

        if($request->accion!='delete'){
            $validator = Validator::make($request->data, $this->rules()[0], $this->rules()[1]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->first(), 'status' => 422], 200);
            }
        }
        
        return response()->json(['message' => 'Validaciones correctas', 'status' => 200], 200);
    }

    /**
     * @brief Método encargado de obtener los miembros convocados a una convocatoria de junta, permitiendo notificar mediante email la confirmació nde su asistencia
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Datos con los convocados asistentes y/o notificados de la convocatoria de junta indicada
     * @throws \Throwable Si no se pudieron obtener los convocados
     */
    public function convocados(Request $request)
    {
        try {

            $convocatoria = Convocatoria::where('id', $request->id)->first();

            $convocados = Convocado::
            with(['usuario' => function ($query) use ($request){
                $query
                ->with(['miembrosJunta' => function ($query) use ($request){
                    $query
                    ->where('idJunta', $request->idJunta)
                    ->with('representacion');
                }]);
            }])
            ->where('idConvocatoria', $request->id);

            $request->notificado!=null ? $convocados = $convocados->where('notificado', $request->notificado) : "";
            $request->asiste!=null ? $convocados = $convocados->where('asiste', $request->asiste) : "";

            if($request->notificar!=null && $request->notificar){

                foreach ($convocados->get() as  $convocado) {
                    $convocado['notificado']=1;
                    $convocado->save();
                }

                Mail::to(Auth::user()->email)->send(new NotificarConvocadosEmail([
                    'asunto' => 'Confirmación asistencia a convocatoria de Junta del centro '.$convocatoria->junta->centro->tipo->nombre.' de '.$convocatoria->junta->centro->nombre,
                    //'usuario' => $convocado->usuario->name,
                    'usuario' => Auth::user()->name,
                    'tipoConvocatoria' => 'Junta',
                    'organo' => $convocatoria->junta->centro->tipo->nombre." de ".$convocatoria->junta->centro->nombre,
                    'fecha' => $convocatoria->fecha,
                    'hora' => $convocatoria->hora,
                    'lugar' => $convocatoria->lugar,
                    'url' => route('convocatoriasJunta')."?filtroJunta={$convocatoria->junta->id}&filtroVigente=1&filtroEstado=1&action=filtrar",
               ]));

                return response()->json(['message' => "La convocatoria del día '$convocatoria->fecha' ha sido notificada vía email a todos sus convocados.  AHORA MISMO SOLO NOTIFICA AL EMAIL DEL USUARIO AUTENTICADO", 'status' => 200], 200);
            }

            return response()->json($convocados->get());
        } catch (\Throwable $th) {
            return response()->json(['errors' => "Ha ocurrido un error al obtener los convocados de la convocatoria del día '$convocatoria->fecha'",'status' => 500], 200);
        }
    }

    /**
     * @brief Método que permitir confirmar o cancelar una asistencia a una convocatoria de junta por parte del usuario
     * @param Request $request Array que contiene todos los datos de entrada que el usuario ha indicado en la petición
     * @return json Mensaje y estado de que la asistencia a la convocatoria de junta se ha confirmado/cancelado correctamente
     * @throws \Throwable Si no se pudo indicar la asitencia de la convocatoria
     */
    public function asistir(Request $request)
    {
        try {
            Convocado::
            where('idUsuario', Auth::user()->id)
            ->where('idConvocatoria', $request->idConvocatoria)
            ->update(['asiste' => $request->asiste]);

            $convocatoria = Convocatoria::where('id', $request->idConvocatoria)->first();

            if($request->asiste==1){
                return response()->json(['message' => "Se ha confirmado la asistencia a la convocatoria de la junta {$convocatoria->junta->centro->nombre} con fecha {$convocatoria->fecha} a las {$convocatoria->hora} en {$convocatoria->lugar}",'status' => 200], 200);
            }
            else{
                return response()->json(['message' => "Se ha cancelado la asistencia a la convocatoria de la junta {$convocatoria->junta->centro->nombre} con fecha {$convocatoria->fecha} a las {$convocatoria->hora} en {$convocatoria->lugar}",'status' => 200], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'Ha ocurrido un error al editar la asistencia de la convocatoria','status' => 500], 200);
        }
    }
}
