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
                'idUsuario' => 1, 
                'idJunta' => 1,
                'representacion' => 1,
                'responsable' => 1
            ],
            [
                'idUsuario' => 2, 
                'idJunta' => 1,
                'representacion' => 2,
                'responsable' => 1
            ],
            [
                'idUsuario' => 3, 
                'idJunta' => 1, 
                'representacion' => 4,
                'responsable' => 1,
            ],
        ];

        foreach($miembrosJunta as $m){
            $miembro = new MiembroJunta();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idJunta = $m['idJunta'];
            $miembro->fechaTomaPosesion = now();
            $miembro->fechaCese = null;
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->responsable = $m['responsable'];
            $miembro->save();
        }

        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        for($i=0; $i<10; $i++){
            MiembroJunta::factory()->count(1)->create();
        }
    }
}
