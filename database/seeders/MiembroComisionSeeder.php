<?php

namespace Database\Seeders;

use App\Models\MiembroComision;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MiembroComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        for($i=0; $i<20; $i++){
            MiembroComision::factory()->count(1)->create();
        }
    }
}
