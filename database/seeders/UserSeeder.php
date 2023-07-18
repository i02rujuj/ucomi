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
        $javier = new User();
        $javier->name = 'Javier Ruiz Jurado';
        $javier->email = 'i02rujuj@uco.es';
        $javier->email_verified_at = now();
        $javier->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $javier->remember_token = Str::random(10);
        $javier->estado = true;
        $javier->save();

        $javier = new User();
        $javier->name = 'Jose Luis Ávila Jiménez';
        $javier->email = 'jlavila@uco.es';
        $javier->email_verified_at = now();
        $javier->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
        $javier->remember_token = Str::random(10);
        $javier->estado = true;
        $javier->save();

    }
}
