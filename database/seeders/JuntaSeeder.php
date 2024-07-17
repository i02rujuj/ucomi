<?php

namespace Database\Seeders;

use App\Models\Junta;
use Illuminate\Database\Seeder;

class JuntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $juntas =[
            [
                'idCentro' => 9, // EPS UCO
                'fechaConstitucion' => '2022-12-02',
            ],
        ];

        foreach($juntas as $j){
            $junta = new Junta();
            $junta->idCentro = $j['idCentro'];
            $junta->fechaConstitucion = $j['fechaConstitucion'];
            $junta->fechaDisolucion= null;
            $junta->save();
        }

    }
}
