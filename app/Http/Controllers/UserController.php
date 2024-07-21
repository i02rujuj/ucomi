<?php

namespace App\Http\Controllers;


use PDF;
use DateTime;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\MiembroJunta;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Exception;
use App\Models\MiembroComision;
use App\Models\MiembroGobierno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{   
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
            $usuario = Auth::User()->name;
            $time = now();

            switch ($request->tipoCertificado) {
                case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                    $idTipoCertificado = config('constants.TIPOS_CERTIFICADO.ACTUAL');
                    $tipoCertificado = "Situación actual";
                    break;

                case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                    $idTipoCertificado = config('constants.TIPOS_CERTIFICADO.HISTORICO');
                    $tipoCertificado = "Histórico";

                    if((isset($request->data['fechaDesde']) && !isset($request->data['fechaHasta'])) || (!isset($request->data['fechaDesde']) && isset($request->data['fechaHasta']))){
                        throw new Exception('Es necesario indicar las dos fechas para realizar la búsqueda o no seleccionar ninguna para obtener todos los resultados');
                    }

                    if(!(!isset($request->data['fechaDesde']) && !isset($request->data['fechaHasta']))){
                        // Validar que fechaHasta no pueda ser mayor a fechaDesde
                        $dateDesde = new DateTime($request->data['fechaDesde']);
                        $dateHasta = new DateTime($request->data['fechaHasta']);

                        if ($dateHasta>$dateDesde) {
                            throw new Exception('La fecha hasta no puede ser superior a la fecha desde');
                        }  
                    }

                    break;
                
                default:
                    throw new Exception('Tipo de certificado incorrecto');
                    break;
            }

            if($request->representacionCentro){
                $dataCentro = MiembroGobierno::
                select('id', 'idCentro', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at')
                ->where('idUsuario', Auth::User()->id);

                switch ($request->tipoCertificado) {
                    case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                        $dataCentro = $dataCentro->whereNull('fechaCese');
                        break;

                    case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                        $dataCentro = $dataCentro->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
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

            if($request->representacionJunta){
                $dataJunta = MiembroJunta::
                select('id', 'idJunta', 'idUsuario', 'idRepresentacion', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at')
                ->where('idUsuario', Auth::User()->id);

                switch ($request->tipoCertificado) {
                    case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                        $dataJunta = $dataJunta->whereNull('fechaCese');
                        break;

                    case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                        $dataJunta = $dataJunta->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
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

            if($request->representacionComision){
                $dataComision = MiembroComision::
                select('id', 'idComision', 'idUsuario', 'idRepresentacion', 'cargo', 'fechaTomaPosesion', 'fechaCese', 'responsable', 'updated_at', 'deleted_at')
                ->where('idUsuario', Auth::User()->id);

                switch ($request->tipoCertificado) {
                    case config('constants.TIPOS_CERTIFICADO.ACTUAL'):
                        $dataComision = $dataComision->whereNull('fechaCese');
                        break;

                    case config('constants.TIPOS_CERTIFICADO.HISTORICO'):
                        $dataComision = $dataComision->whereBetween('fechaTomaPosesion', [$dateDesde, $dateHasta]);
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

            $data = [
                    'idTipo' => $idTipoCertificado,
                    'tipo' => $tipoCertificado,
                    'usuario' => $usuario,
                    'dataCentro' => $dataCentro,
                    'dataJunta' => $dataJunta,
                    'dataComision' => $dataComision,
            ];

            $pdf = PDF::loadView('certificados.certificado', $data);
            $pdf->setOptions([
                'isRemoteEnabled' => true,
            ]);
            $pdf->render();
            //dd($pdf);
            return $pdf->stream("Certificado $tipoCertificado $usuario $time.pdf");
        } catch (\Throwable $th) {
           
        }   
    }
}
