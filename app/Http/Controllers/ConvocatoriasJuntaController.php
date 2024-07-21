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
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\Notification\NotificationInterface;

class ConvocatoriasJuntaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $convocatorias = Convocatoria::select('id', 'idJunta', 'idTipo', 'lugar', 'fecha', 'hora', 'acta', 'updated_at', 'convocatorias.deleted_at');
            $juntas = Junta::select('id', 'idCentro', 'fechaConstitucion', 'fechaDisolucion', 'updated_at', 'deleted_at');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $convocatorias = $convocatorias
                ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder
                    ->whereHas('centro', function($builder) use ($datosResponsableCentro){
                        $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                    });
                }); 
                
                $juntas = $juntas
                ->whereIn('idCentro', $datosResponsableCentro['idCentros']);
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $convocatorias = $convocatorias->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
                $juntas = $juntas->whereIn('id', $datosResponsableJunta['idJuntas']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroJunta']=null;
                    $request['filtroVigente']=null;
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
            ->orderBy('fecha')  
            ->orderBy('hora')          
            ->orderBy('idJunta')
            ->orderBy('idComision')
            ->orderBy('idTipo')
            ->orderBy('updated_at','desc')
            ->paginate(5);

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
                'filtroVigente' => $request['filtroVigente'],
                'filtroEstado' => $request['filtroEstado'],
                'action' => $request['action'],
            ]);

        } catch (\Throwable $th) {
            toastr('No se pudieron obtener las convocatorias.', NotificationInterface::ERROR, ' ');
            return redirect()->route('home')->with('errors', 'No se pudieron obtener las convocatorias.');
        }
    }
    
    public function store(Request $request)
    {
        try {
            $request['accion']='add';
            $validation = $this->validateConvocatoria($request);
            if($validation->original['status']!=200){
                return $validation;
            }

            if(isset($request->data['acta'])){
                $url_acta = Helper::subirImagenCloudinary($request->data['acta'], "actasJuntas");
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

            toastr("La convocatoria del día '$convocatoria->fecha' se ha añadido correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "La convocatoria  del día '$convocatoria->fecha' se ha añadido correctamente.", 'status' => 200], 200);

        } catch (\Throwable $th) {
            toastr("Error al añadir la convocatoria del día '$convocatoria->fecha'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al añadir la convocatoria del día '$convocatoria->fecha'", 'status' => 422], 200);
        }
    }

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
                $url_acta = Helper::subirImagenCloudinary($request->data['acta'], "actasJuntas");
                $convocatoria->acta = $url_acta;
            }

            $convocatoria->idTipo = $request->data['idTipo'];
            $convocatoria->lugar = $request->data['lugar'];
            $convocatoria->fecha = $request->data['fecha'];
            $convocatoria->hora = $request->data['hora'];
            $convocatoria->save();
           
            toastr("La convocatoria del día '$convocatoria->fecha' se ha actualizado correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "La convocatoria del día '$convocatoria->fecha' se ha actualizado correctamente.", 'status' => 200], 200);
            
        } catch (\Throwable $th) {
            toastr("Error al actualizar la convocatoria del día '$convocatoria->fecha'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al actualizar la convocatoria del día '$convocatoria->fecha'", 'status' => 422], 200);
        }
    }

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

            toastr("La convocatoria del día '{$convocatoria->fecha}' se ha eliminado correctamente.", NotificationInterface::SUCCESS, ' ');
            return response()->json(['message' => "La convocatoria del día '{$convocatoria->fecha}' se ha eliminado correctamente.",'status' => 200], 200);

        } catch (\Throwable $th) {
            toastr("Error al eliminar la convocatoria del día '{$convocatoria->fecha}'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Error al eliminar la convocatoria del día '{$convocatoria->fecha}'",'status' => 422], 200);
        }
    }

    public function get(Request $request)
    {
        try {
            $convocatoria = Convocatoria::withTrashed()->where('id', $request->id)->first();
            if (!$convocatoria) {
                throw new Exception();
            }

            return response()->json($convocatoria);
        } catch (\Throwable $th) {
            toastr("No se ha encontrado la convocatoria.", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => 'No se ha encontrado la convocatoria.','status' => 422], 200);
        }
    }

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

    public function convocados(Request $request)
    {
        try {
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

            return response()->json($convocados->get());
        } catch (\Throwable $th) {
            toastr("Ha ocurrido un error al obtener los convocados de la convocatoria del día '$request->fecha'", NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => "Ha ocurrido un error al obtener los convocados de la convocatoria del día '$request->fecha'",'status' => 422], 200);
        }
    }

    public function asistir(Request $request)
    {
        try {
            Convocado::
            where('idUsuario', Auth::user()->id)
            ->where('idConvocatoria', $request->idConvocatoria)
            ->update(['asiste' => $request->asiste]);

            if($request->asiste==1){
                toastr('Se ha confirmado la asistencia a la convocatoria', NotificationInterface::SUCCESS, ' ');
                return response()->json(['message' => 'Se ha confirmado la asistencia de la convocatoria','status' => 200], 200);
            }
            else{
                toastr('Se ha cancelado la asistencia a la convocatoria', NotificationInterface::SUCCESS, ' ');
                return response()->json(['message' => 'Se ha cancelado la asistencia a la convocatoria','status' => 200], 200);
            }

        } catch (\Throwable $th) {
            toastr('Ha ocurrido un error al editar la asistencia de la convocatoria', NotificationInterface::ERROR, ' ');
            return response()->json(['errors' => 'Ha ocurrido un error al editar la asistencia de la convocatoria','status' => 422], 200);
        }
    }
}
