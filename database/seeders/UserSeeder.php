<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre'=>'Manuel Cañas Ramírez', 
                'email'=>'epsc.director@uco.es', 
                'rol'=>'responsable_centro',
            ],
            [
                'nombre'=>'Luis Manuel Fernández de Ahumada', 
                'email'=>'epsc.secretaria@uco.es', 
                'rol'=>'responsable_centro',
            ],
            [
                'nombre'=>'Francisco Ramón Lara Raya', 
                'email'=>'epsc.ord.acad@uco.es',
                'rol'=>'responsable_junta', 
            ],
            [
                'nombre'=>'Joost van Duijn', 
                'email'=>'epsc.relexteriores@uco.es', 
                'rol'=>'responsable_junta',
            ],
            [
                'nombre'=>'Isabel Moreno García', 
                'email'=>'calidad.epsc@uco.es', 
                'rol'=>'responsable_comision',
            ],
            [
                'nombre'=>'Rosa María Relaño Luna', 
                'email'=>'direccioneps@uco.es', 
                'rol'=>'responsable_comision',
            ],
            [
                'nombre'=>'José Luis Ávila Jiménez', 
                'email'=>'jlavila@uco.es', 
                'rol'=>'admin',
            ],
            [
                'nombre'=>'Javier Ruiz Jurado', 
                'email'=>'i02rujuj@uco.es', 
                'rol'=>'admin',
            ],
        ];

        foreach($usuarios as $user){
            $u = new User();
            $u->name = $user['nombre'];
            $u->email = $user['email'];
            $u->email_verified_at = now();
            $u->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
            $u->remember_token = Str::random(10);
            $u->estado = true;
            $u->save();

            $u->assignRole($user['rol']);
        }

        $users = User::factory()->count(100)->create();

    }
}
