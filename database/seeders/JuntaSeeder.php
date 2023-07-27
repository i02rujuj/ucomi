<?php

namespace Database\Seeders;

use App\Models\Junta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JuntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $juntas =[
            [
                'idCentro'=>1, 
            ],
            [ 
                'idCentro'=>9, 
            ],
        ];

        foreach($juntas as $j){
            $junta = new Junta();
            $junta->idCentro = $j['idCentro'];
            $junta->fechaConstitucion = now();
            $junta->fechaDisolucion= null;
            $junta->estado = true;
            $junta->save();
        }

    }
}
