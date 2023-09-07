<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TipoConvocatoria;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TipoCentroSeeder::class,
            CentroSeeder::class,
            JuntaSeeder::class,
            RepresentacionGobiernoSeeder::class,
            MiembroGobiernoSeeder::class,
            RepresentacionGeneralSeeder::class,
            MiembroJuntaSeeder::class,
            TipoConvocatoriaSeeder::class,
            ComisionSeeder::class,
            MiembroComisionSeeder::class,
            RolesSeeder::class,
        ]);
    }
}
