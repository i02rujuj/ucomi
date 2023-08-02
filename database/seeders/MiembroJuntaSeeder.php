<?php

namespace Database\Seeders;

use App\Models\MiembroJunta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MiembroJuntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        for($i=0; $i<20; $i++){
            MiembroJunta::factory()->count(1)->create();
        }
    }
}
