<?php

namespace Database\Seeders;

use App\Models\MiembroGobierno;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MiembroGobiernoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $miembrosGobierno =[
            [
                'idUsuario' => 1, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 1
            ],
            [
                'idUsuario' => 2, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 2
            ],
            [
                'idUsuario' => 3, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 4
            ],
            [
                'idUsuario' => 4, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 4
            ],
            [
                'idUsuario' => 5, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 4
            ],
            [
                'idUsuario' => 6, 
                'idCentro' => 9, 
                'idJunta' => 1,
                'representacion' => 5
            ],
        ];

        foreach($miembrosGobierno as $m){
            $miembro = new MiembroGobierno();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idCentro = $m['idCentro'];
            $miembro->idJunta = $m['idJunta'];
            $miembro->fechaTomaPosesion = now();
            $miembro->fechaCese = null;
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->save();
        }
    }
}
