<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                return response()->json(['error' => 'No se ha encontrado el usuario.'], 404);
            }
            return response()->json($user);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'No se ha encontrado el usuario.'], 404);
        }
    }
}
