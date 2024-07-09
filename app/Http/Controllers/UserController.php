<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    public function index()
    {
        return view('perfil');
    }

    public function certificados()
    {
        return view('certificados');
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
	    $data = [
	            'title' => 'Tipo de certificado...',
	            'date' => date('d/m/Y'),
                'users' => User::get(),
	    ];

        $pdf = PDF::loadView('certificado', $data);
        return $pdf->download('certificado.pdf');
    }
}
