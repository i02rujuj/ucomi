<?php

namespace App\Http\Controllers;


use PDF;
use DateTime;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Convocado;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use App\Models\MiembroComision;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Flasher\Prime\Notification\NotificationInterface;

class UserController extends Controller
{   

    public function index(){

        $users=null;

        if(Auth::user()->hasRole('admin')){
            $users=User::all();
        }

        return view('certificados')->with(['users'=>$users]);
    }

    public function get(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                return response()->json(['errors' => 'No se ha encontrado el usuario.', 'status' => 422], 200);
            }

            $user['roles'] = $user->getRoleNames();

            return response()->json($user);

        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se ha encontrado el usuario.', 'status' => 422], 200);
        }
    }

    public function all()
    {
        try {
            $usuarios = User::all();
            if (!$usuarios) {
                return response()->json(['errors' => 'No se han podido obtener los usuarios.', 'status' => 422], 200);
            }
            return response()->json($usuarios);
        } catch (\Throwable $th) {
            return response()->json(['errors' => 'No se han podido obtener los usuarios.', 'status' => 422], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'oldPassword' => 'required',
                'newPassword' => 'required|min:8|different:oldPassword|confirmed',
                'newPassword_confirmation' => 'required'
            ], [
                'oldPassword.required' => 'La contraseña actual es obligatoria.',
                'newPassword.required' => 'La contraseña nueva es obligatoria.',
                'newPassword.min' => 'La contraseña nueva debe tener al menos 8 caracteres.',
                'newPassword.confirmed' => 'La confirmación de la contraseña nueva no coincide.',
                'newPassword.different' => 'La nueva contraseña debe ser diferente a la anterior',
                'newPassword_confirmation.required' => 'La confirmación de la contraseña es obligatoria.'
            ]);
            if ($validator->fails()) {
                // Si la validación falla, redirige de vuelta con los errores
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (Hash::check($request->oldPassword, Auth::user()->password)) {
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->newPassword);
                $user->email_verified_at = now();
                $user->save();
                return redirect()->route('perfil')->with('success', 'Contraseña actualizada correctamente');
            } else {
                return redirect()->route('perfil')->with('error', 'La contraseña actual es incorrecta');
            }
        } catch (\Throwable $th) {
            return redirect()->route('perfil')->with('error', 'Error al actualizar la contraseña: ' . $th->getMessage());
        }
    }

    public function saveImagePerfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'imagen' => 'required|max:1000|mimes:png,jpg,jpeg',
        ], [
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.max' => 'El nombre de la imagen no puede exceder los 1000 caracteres.',
            'imagen.mimes' => 'El logo debe estar en formato jpeg, jpg ó png.',
        ]);
        if ($validator->fails()) {
            // Si la validación falla, redirige de vuelta con los errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        switch ($request->input('action')) {
            case 'save':
                $url_image = Helper::subirImagenCloudinary($request->file('imagen'), "userImages");
                $user->image = $url_image;
                $user->save();

                return redirect()->route('perfil')->with('success', 'Imagen de perfil actualizada correctamente');
            break;
    
            case 'delete':
                $user->image = null;
                $user->save();
                return redirect()->route('perfil')->with('success', 'Imagen de perfil eliminada correctamente');
                break;
        } 
    }

    public function generarCertificado(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipoCertificado' => 'required',
                'representaciones' => 'required|min:1',
                'idUsuario' => Auth::user()->hasRole('admin') ? 'required' : 'nullable',
            ]
            ,[
                // Mensajes error tipoCertificado
                'tipoCertificado.required' => 'El tipo de certificado es obligatorio.',
                // Mensajes error representaciones
                'representaciones.required' => 'Debe seleccionar al menos una opción',
                // Mensajes error idUsuario
                'idUsuario.required' => 'El usuario es requerido',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $usuario = null;
            $time = now();
            $dataCentro=null;
            $dataJunta=null;
            $dataComision=null;
            $dateDesde=null;
            $dateHasta=null;

            switch ($request->tipoCertificado) {
                case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                    $idTipoCertificado = config('constants.TIPOS_CERTIFICADO.ACTUAL');
                    $tipoCertificado = "Situación actual";
                    break;

                case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                    $idTipoCertificado = config('constants.TIPOS_CERTIFICADO.HISTORICO');
                    $tipoCertificado = "Histórico";

                    if((isset($request->fechaDesde) && !isset($request->fechaHasta)) || (!isset($request->fechaDesde) && isset($request->fechaHasta))){
                        toastr('Para obtener el certificado histórico es necesario indicar las dos fechas para realizar la búsqueda o no seleccionar ninguna para obtener todos los resultados', NotificationInterface::ERROR, ' ');
                        return redirect()->back();
                    }

                    if(!(!isset($request->fechaDesde) && !isset($request->fechaHasta))){
                        // Validar que fechaHasta no pueda ser mayor a fechaDesde
                        $dateDesde = new DateTime($request->fechaDesde);
                        $dateHasta = new DateTime($request->fechaHasta);

                        if ($dateHasta<$dateDesde) {
                            toastr('La fecha hasta no puede anterior a la fecha desde', NotificationInterface::ERROR, ' ');
                            return redirect()->back();
                        }  
                    }

                    break;
                
                default:
                    toastr('Tipo de certificado incorrecto', NotificationInterface::ERROR, ' ');
                    return redirect()->back();
                    break;
            }

            foreach ($request->representaciones as  $representacion) {

                if($representacion=='centro'){

                    $dataCentro = MiembroGobierno::
                    select('id', 'idCentro', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
                    
                    if(Auth::user()->hasRole('admin')){
                        $dataCentro = $dataCentro->where('idUsuario', $request->idUsuario);
                        $usuario = User::select('name')->where('id', $request->idUsuario)->first()->name;
                    }
                    else{
                        $dataCentro = $dataCentro->where('idUsuario', Auth::User()->id);
                        $usuario = Auth::User()->name;
                    }

                    switch ($request->tipoCertificado) {
                        case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                            $dataCentro = $dataCentro->whereNull('fechaCese');
                            break;

                        case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                            if($dateDesde!=null && $dateHasta!=null){
                                $dataCentro = $dataCentro->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
                            }
                            break;
                    }

                    if($request->responsable){
                        $dataCentro = $dataCentro->where('responsable', 1);
                    }
        
                    $dataCentro = $dataCentro
                        ->orderBy('deleted_at')
                        ->orderBy('fechaCese')
                        ->orderBy('updated_at','desc')
                        ->orderBy('idRepresentacion')
                        ->get();
                }

                if($representacion=='junta'){

                    $dataJunta = MiembroJunta::
                    select('id', 'idJunta', 'idUsuario', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');
                    
                    if(Auth::user()->hasRole('admin')){
                        $dataJunta = $dataJunta->where('idUsuario', $request->idUsuario);
                        $usuario = User::select('name')->where('id', $request->idUsuario)->first()->name;
                    }
                    else{
                        $dataJunta = $dataJunta->where('idUsuario', Auth::User()->id);
                        $usuario = Auth::user()->name;
                    }

                    switch ($request->tipoCertificado) {
                        case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                            $dataJunta = $dataJunta->whereNull('fechaCese');
                            break;

                        case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                            if($dateDesde!=null && $dateHasta!=null){
                                $dataJunta = $dataJunta->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
                            }
                            break;
                    }

                    if($request->responsable){
                        $dataJunta = $dataJunta->where('responsable', 1);
                    }
        
                    $dataJunta = $dataJunta
                        ->orderBy('deleted_at')
                        ->orderBy('fechaCese')
                        ->orderBy('updated_at','desc')
                        ->orderBy('idRepresentacion')
                        ->get();
                }

                if($representacion=='comision'){
                    $dataComision = MiembroComision::
                    select('id', 'idComision', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at');

                    if(Auth::user()->hasRole('admin')){
                        $dataComision = $dataComision->where('idUsuario', $request->idUsuario);
                        $usuario = User::select('name')->where('id', $request->idUsuario)->first()->name;
                    }
                    else{
                        $dataComision = $dataComision->where('idUsuario', Auth::User()->id);
                        $usuario = Auth::User()->name;
                    }

                    switch ($request->tipoCertificado) {
                        case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                            $dataComision = $dataComision->whereNull('fechaCese');
                            break;

                        case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                            if($dateDesde!=null && $dateHasta!=null){
                                $dataComision = $dataComision->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
                            }
                            break;
                    }

                    if($request->responsable){
                        $dataComision = $dataComision->where('responsable', 1);
                    }
        
                    $dataComision = $dataComision
                        ->orderBy('deleted_at')
                        ->orderBy('fechaCese')
                        ->orderBy('updated_at','desc')
                        ->orderBy('idRepresentacion')
                        ->get();
                }
            }

            $data = [
                    'idTipo' => $idTipoCertificado,
                    'tipo' => $tipoCertificado,
                    'usuario' => $usuario,
                    'dataCentro' => $dataCentro,
                    'dataJunta' => $dataJunta,
                    'dataComision' => $dataComision,
                    'dateDesde' => $dateDesde!=null ? $dateDesde->format('d/m/Y') : null,
                    'dateHasta' => $dateHasta!=null ? $dateHasta->format('d/m/Y') : null,
            ];

            $pdf = PDF::loadView('certificados.certificado', $data);
            $pdf->render();
            //dd($pdf);
            return $pdf->download("Certificado $tipoCertificado $usuario $time.pdf");
        } catch (\Throwable $th) {
            toastr('Ha ocurrido un error al generar el certificado'.$th->getMessage(), NotificationInterface::ERROR, ' ');
            return redirect()->back();
        }   
    }

    public function generarCertificadoAsistencia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipoCertificado' => 'required',
                'tiposConvocatoria' => 'required|min:1',
                'idUsuario' => Auth::user()->hasRole('admin') ? 'required' : 'nullable',
            ]
            ,[
                // Mensajes error tipoCertificado
                'tipoCertificado.required' => 'El tipo de certificado es obligatorio.',
                // Mensajes error tiposConvocatoria
                'tiposConvocatoria.required' => 'Debe seleccionar al menos una opción',
                // Mensajes error idUsuario
                'idUsuario.required' => 'El usuario es requerido',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $usuario = null;
            $time = now();
            $dataJunta=null;
            $dataComision=null;
            $dateDesde=null;
            $dateHasta=null;

            switch ($request->tipoCertificado) {
                case config('constants.TIPOS_CERTIFICADO.CONVOCATORIA'):
                    $idTipoCertificado = config('constants.TIPOS_CERTIFICADO.CONVOCATORIA');
                    $tipoCertificado = "Asistencia a convocatorias";

                    if((isset($request->fechasAsistenciaDesde) && !isset($request->fechasAsistenciaHasta)) || (!isset($request->fechasAsistenciaDesde) && isset($request->fechasAsistenciaHasta))){
                        toastr('Para obtener el certificado es necesario indicar las dos fechas para realizar la búsqueda o no seleccionar ninguna para obtener todos los resultados', NotificationInterface::ERROR, ' ');
                        return redirect()->back();
                    }

                    if(!(!isset($request->fechasAsistenciaDesde) && !isset($request->fechasAsistenciaHasta))){
                        // Validar que fechaHasta no pueda ser mayor a fechaDesde
                        $dateDesde = new DateTime($request->fechasAsistenciaDesde);
                        $dateHasta = new DateTime($request->fechasAsistenciaHasta);

                        if ($dateHasta<$dateDesde) {
                            toastr('La fecha de hasta no puede anterior a la fecha desde', NotificationInterface::ERROR, ' ');
                            return redirect()->back();
                        }  
                    }

                    break;
                
                default:
                    toastr('Tipo de certificado incorrecto', NotificationInterface::ERROR, ' ');
                    return redirect()->back();
                    break;
            }
            foreach ($request->tiposConvocatoria as  $tipoConvocatoria) {

                if($tipoConvocatoria=='junta'){
                    $dataJunta = Convocado::select('id', 'idUsuario', 'idConvocatoria', 'asiste', 'notificado', 'updated_at', 'deleted_at');
                    
                    if(Auth::user()->hasRole('admin')){
                        $dataJunta = $dataJunta->where('idUsuario', $request->idUsuario);
                        $usuario = User::select('name')->where('id', $request->idUsuario)->first()->name;
                    }
                    else{
                        $dataJunta = $dataJunta->where('idUsuario', Auth::User()->id);
                        $usuario = Auth::User()->name;
                    }

                    if(isset($request->asistencia[1])){
                        $dataJunta = $dataJunta->where('asiste', 1);
                    }
    
                    if(isset($request->asistencia[2])){
                        $dataJunta = $dataJunta->where('asiste', 0);
                    }

                    $dataJunta = $dataJunta
                        ->whereHas('convocatoria', function($builder) use($dateDesde, $dateHasta){
                            if($dateDesde!=null && $dateHasta!=null){
                                $builder = $builder->whereBetween('fecha', [$dateDesde, $dateHasta]);
                            }
                            return $builder->whereHas('junta', function($builder){
                                
                            });
                        })
                        ->orderBy('deleted_at')
                        ->orderBy('idConvocatoria')
                        ->orderBy('updated_at','desc')
                        ->get();
                }

                if($tipoConvocatoria=='comision'){
                    $dataComision = Convocado::select('id', 'idUsuario', 'idConvocatoria', 'asiste', 'notificado', 'updated_at', 'deleted_at');
                    
                    if(Auth::user()->hasRole('admin')){
                        $dataComision = $dataComision->where('idUsuario', $request->idUsuario);
                        $usuario = User::select('name')->where('id', $request->idUsuario)->first()->name;
                    }
                    else{
                        $dataComision = $dataComision->where('idUsuario', Auth::User()->id);
                        $usuario = Auth::User()->name;
                    }

                    if(isset($request->asistencia[1])){
                        $dataComision = $dataComision->where('asiste', 1);
                    }
    
                    if(isset($request->asistencia[2])){
                        $dataComision = $dataComision->where('asiste', 0);
                    }

                    $dataComision = $dataComision
                        ->whereHas('convocatoria', function($builder) use($dateDesde, $dateHasta){
                                if($dateDesde!=null && $dateHasta!=null){
                                    $builder = $builder->whereBetween('fecha', [$dateDesde, $dateHasta]);
                                }
                            
                                return $builder->whereHas('comision', function($builder){
                            });
                        })
                        ->orderBy('deleted_at')
                        ->orderBy('idConvocatoria')
                        ->orderBy('updated_at','desc')
                        ->get();
                }
            }

            $data = [
                    'idTipo' => $idTipoCertificado,
                    'tipo' => $tipoCertificado,
                    'usuario' => $usuario,
                    'dataJunta' => $dataJunta,
                    'dataComision' => $dataComision,
                    'dateDesde' => $dateDesde!=null ? $dateDesde->format('d/m/Y') : null,
                    'dateHasta' => $dateHasta!=null ? $dateHasta->format('d/m/Y') : null,
            ];

            $pdf = PDF::loadView('certificados.certificadoConvocatoria', $data);
            $pdf->render();
            return $pdf->download("Certificado $tipoCertificado $usuario $time.pdf");
        } catch (\Throwable $th) {
            toastr('Ha ocurrido un error al generar el certificado'.$th->getMessage(), NotificationInterface::ERROR, ' ');
            return redirect()->back();
        }   
    }
}
