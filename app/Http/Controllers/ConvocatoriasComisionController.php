<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Helper;
use App\Models\Comision;
use App\Models\Convocado;
use App\Models\Convocatoria;
use Illuminate\Http\Request;
use App\Models\TipoConvocatoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\Notification\NotificationInterface;

class ConvocatoriasComisionController extends Controller
{
    public function index(Request $request)
    {
        try {

            $convocatorias = Convocatoria::select('id', 'idComision', 'idTipo', 'lugar', 'fecha', 'hora', 'acta', 'updated_at', 'deleted_at');
            $comisiones = Comision::select('id', 'idJunta', 'nombre', 'descripcion', 'fechaConstitucion', 'fechaDisolucion', 'updated_at', 'deleted_at');

            if($datosResponsableCentro = Auth::user()->esResponsableDatos('centro')['centros']){
                $convocatorias = $convocatorias
                ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder
                    ->whereHas('centro', function($builder) use ($datosResponsableCentro){
                        $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                    });
                }); 
                
                $comisiones = $comisiones
                ->whereHas('junta', function($builder) use ($datosResponsableCentro){
                    return $builder
                    ->whereHas('centro', function($builder) use ($datosResponsableCentro){
                        $builder->whereIn('idCentro', $datosResponsableCentro['idCentros']);
                    });
                });
            }

            if($datosResponsableJunta = Auth::user()->esResponsableDatos('junta')['juntas']){
                $convocatorias = $convocatorias
                ->whereHas('junta', function($builder) use ($datosResponsableJunta){
                    return $builder->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
                });
                $comisiones = $comisiones->whereIn('idJunta', $datosResponsableJunta['idJuntas']);
            }

            if($datosResponsableComision = Auth::user()->esResponsableDatos('comision')['comisiones']){
                $convocatorias = $convocatorias
                ->whereIn('idComision', $datosResponsableComision['idComisiones']);
                $comisiones = $comisiones->whereIn('id', $datosResponsableComision['idComisiones']);
            }

            switch ($request->input('action')) {
                case 'limpiar':
                    $request['filtroComision']=null;
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
            $comisiones=$comisiones->get();
            $convocatorias = $convocatorias
            ->whereNot('idComision', null)
            ->orderBy('deleted_at')
            ->orderBy('updated_at','desc')
            ->orderBy('fecha')  
            ->orderBy('hora')          
            ->orderBy('idComision')
            ->orderBy('idComision')
            ->orderBy('idTipo')
            ->paginate(5);

            if($request->input('action')=='limpiar'){
                return redirect()->route('convocatorias')->with([
                    'convocatorias' => $convocatorias,
                    'comisiones' => $comisiones, 
                    'tipos' => $tipos, 
                ]);
            }

            return view('convocatoriasComision', [
                'convocatorias' => $convocatorias,
                'comisiones' => $comisiones, 
                'tipos' => $tipos, 
                'filtroComision' => $request['filtroComision'],
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
                $url_acta = Helper::subirPDFCloudinary($request->data['acta'], "actasComisiones");
            }

            $convocatoria = Convocatoria::create([
                "idComision" => $request->data['idComision'],
                "idTipo" => $request->data['idTipo'],
                "lugar" => $request->data['lugar'],
                "fecha" => $request->data['fecha'],
                "hora" => $request->data['hora'],
                "acta" => isset($url_acta) ? $url_acta : null,
            ]);

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
                $url_acta = Helper::subirPDFCloudinary($request->data['acta'], "actasComisiones");
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
            $convocatoria = Convocatoria::where('id', $request->id)->first();
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
            'idComision' => 'required|integer|exists:App\Models\Comision,id',
            'idTipo' => 'required|integer|exists:App\Models\TipoConvocatoria,id',
            'lugar' => 'required|max:100|string',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'acta' => 'nullable|max:1000|mimes:pdf',
        ]; 
        
        $rules_message = [
            // Mensajes error idComision
            'idComision.required' => 'La comisión es obligatoria.',
            'idComision.integer' => 'La comisión debe ser un entero.',
            'idComision.exists' => 'La comisión seleccionada no existe.',
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
                ->with(['miembrosComision' => function ($query) use ($request){
                    $query
                    ->where('idComision', $request->idComision)
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
