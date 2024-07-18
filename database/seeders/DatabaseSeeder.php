<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UserSeeder::class,
            TipoCentroSeeder::class,
            CentroSeeder::class,
            JuntaSeeder::class,
            RepresentacionSeeder::class,
            MiembroGobiernoSeeder::class,
            MiembroJuntaSeeder::class,
            TipoConvocatoriaSeeder::class,
            ComisionSeeder::class,
            MiembroComisionSeeder::class,
            ConvocatoriaSeeder::class,
        ]);
    }
}
