<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\User;
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
                return response()->json(['error' => 'No se ha encontrado el usuario.'], 404);
            }

            $user['roles'] = $user->getRoleNames();

            return response()->json($user);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el usuario.'], 404);
        }
    }

    public function index()
    {
        return view('perfil');
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
        $profile_image = $request->file('imagen');
        if ($profile_image) {
            $filename = time() . '.' . $profile_image->getClientOriginalExtension();
            //$path = public_path('img/' . $filename);

            // Verificar si el usuario ya tiene una imagen guardada
            if ($user->image) {
                // Si tiene una imagen guardada, elimina el archivo de la imagen
                //$image_path = public_path('img/' . $user->image);
                //if (file_exists($image_path)) {
                //    unlink($image_path);
                //}
                Cloudinary::destroy($user->image);
            }

            // Almacenamiento local en servidor
            //$profile_image->move(public_path('img/'), $filename);
            //$profile_image->storeAs('img', $filename,['disk' => 'public_uploads']);
            $result = cloudinary()->upload($profile_image->getRealPath(), [
                'folder' => 'userImages',
                'transformation' => [
                          'width' => 112,
                          'height' => 112
                ]
            ]);
            //$result = $profile_image->storeOnCloudinary("userImages", $filename, ["width" => 112, "height"=>112]);
            $user->image = $result->getPublicId();
            $user->save();
            return redirect()->route('perfil')->with('success', 'Imagen de perfil actualizada correctamente');
            
        } else {
            // Si el usuario no ha seleccionado una imagen, establece una imagen predeterminada
            if (!$user->image) {
                $user->image = 'default_image.jpg';
                $user->save();
            }
            return redirect()->route('perfil')->with('error', 'Selecciona una imagen para actualizar tu perfil');
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
