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
        $javier = User::factory(User::class)->create([
            'name' => 'Javier Ruiz Jurado',
            'email' => 'i02rujuj@uco.es',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', /* Contraseña: password */
            'remember_token' => Str::random(10),
            'estado' => true
        ]);

        $joseluis = User::factory(User::class)->create([
            'name' => 'Jose Luis Ávila Jiménez',
            'email' => 'jlavila@uco.es',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', /* Contraseña: password */
            'remember_token' => Str::random(10),
            'estado' => true
        ]);
    }
}
