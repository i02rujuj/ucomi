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

        // USUARIOS

        $javier = new User();
        $javier->name = 'Javier Ruiz Jurado';
        $javier->email = 'i02rujuj@uco.es';
        $javier->email_verified_at = now();
        $javier->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $javier->remember_token = Str::random(10);
        $javier->estado = true;
        $javier->save();

        $jose = new User();
        $jose->name = 'Jose Luis Ávila Jiménez';
        $jose->email = 'jlavila@uco.es';
        $jose->email_verified_at = now();
        $jose->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $jose->remember_token = Str::random(10);
        $jose->estado = true;
        $jose->save();

        // MIEMBROS UCO
        $usuario = new User();
        $usuario->name = 'Manuel Cañas Ramírez';
        $usuario->email = 'epsc.director@uco.es';
        $usuario->email_verified_at = now();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $usuario->remember_token = Str::random(10);
        $usuario->estado = true;
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Luis Manuel Fernández de Ahumada';
        $usuario->email = 'epsc.secretaria@uco.es';
        $usuario->email_verified_at = now();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $usuario->remember_token = Str::random(10);
        $usuario->estado = true;
        $usuario->save();    

        $usuario = new User();
        $usuario->name = 'Francisco Ramón Lara Raya';
        $usuario->email = 'epsc.ord.acad@uco.es';
        $usuario->email_verified_at = now();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $usuario->remember_token = Str::random(10);
        $usuario->estado = true;
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Joost van Duijn';
        $usuario->email = 'epsc.relexteriores@uco.es';
        $usuario->email_verified_at = now();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $usuario->remember_token = Str::random(10);
        $usuario->estado = true;
        $usuario->save();

        $usuario = new User();
        $usuario->name = 'Isabel Moreno García';
        $usuario->email = 'calidad.epsc@uco.es';
        $usuario->email_verified_at = now();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $usuario->remember_token = Str::random(10);
        $usuario->estado = true;
        $usuario->save();

    }
}
