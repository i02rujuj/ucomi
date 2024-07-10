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
                'id' => 1,
                'nombre'=>'Manuel Cañas Ramírez', 
                'email'=>'epsc.director@uco.es', 
            ],
            [
                'id' => 2,
                'nombre'=>'Luis Manuel Fernández de Ahumada', 
                'email'=>'epsc.secretaria@uco.es', 
            ],
            [
                'id' => 3,
                'nombre'=>'Francisco Ramón Lara Raya', 
                'email'=>'epsc.ord.acad@uco.es',
            ],
            [
                'id' => 4,
                'nombre'=>'Joost van Duijn', 
                'email'=>'epsc.relexteriores@uco.es', 
            ],
            [
                'id' => 5,
                'nombre'=>'Isabel Moreno García', 
                'email'=>'calidad.epsc@uco.es', 
            ],
            [
                'id' => 6,
                'nombre'=>'Rosa María Relaño Luna', 
                'email'=>'direccioneps@uco.es', 
            ],
            [
                'id' => 7,
                'nombre'=>'José Luis Ávila Jiménez', 
                'email'=>'jlavila@uco.es', 
                'rol'=>'admin',
            ],
            [
                'id' => 8,
                'nombre'=>'Javier Ruiz Jurado', 
                'email'=>'i02rujuj@uco.es', 
                'rol'=>'admin',
            ],
            [
                'id' => 9,
                'nombre'=>'Paula Cebrián Navarro', 
                'email'=>'miembrocentro@uco.es', 
            ],
            [
                'id' => 10,
                'nombre'=>'Rocío Ruiz Jurado', 
                'email'=>'miembrojunta@uco.es', 
            ],
            [
                'id' => 11,
                'nombre'=>'Rafael Jurado Torres', 
                'email'=>'miembrocomision@uco.es', 
            ],
        ];

        foreach($usuarios as $user){
            $u = new User();
            $u->name = $user['nombre'];
            $u->email = $user['email'];
            $u->email_verified_at = now();
            $u->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
            $u->remember_token = Str::random(10);
            $u->save();

            if(isset($user['rol'])){
                $u->assignRole($user['rol']);
            }
        }

        User::factory()->count(100)->create();

    }
}
