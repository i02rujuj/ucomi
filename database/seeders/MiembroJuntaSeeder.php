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

        $miembrosJunta =[
            [
                'idUsuario' => 3, 
                'idJunta' => 1,
                'representacion' => 4
            ],
            [
                'idUsuario' => 4, 
                'idJunta' => 1,
                'representacion' => 4
            ],
        ];

        foreach($miembrosJunta as $m){
            $miembro = new MiembroJunta();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idJunta = $m['idJunta'];
            $miembro->fechaTomaPosesion = now();
            $miembro->fechaCese = null;
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->estado = 1;
            $miembro->save();
        }

        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        for($i=0; $i<20; $i++){
            MiembroJunta::factory()->count(1)->create();
        }
    }
}
