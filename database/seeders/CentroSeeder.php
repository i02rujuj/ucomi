<?php

namespace Database\Seeders;

use App\Models\Centro;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciencias = new Centro();
        $ciencias->nombre = 'Facultad de Ciencias';
        $ciencias->direccion = 'Campus de Rabanales';
        $ciencias->tipo = "propio";
        $ciencias->estado = true;
        $ciencias->save();
    }
}
