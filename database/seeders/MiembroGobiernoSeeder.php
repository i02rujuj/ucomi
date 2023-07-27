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
                'idUsuario'=>4, 
                'idCentro'=>1, 
                'representacion'=>1
            ],
            [
                'idUsuario'=>3, 
                'idCentro'=>1, 
                'representacion'=>2
            ],
            [
                'idUsuario'=>2, 
                'idCentro'=>9, 
                'representacion'=>1
            ],
            [
                'idUsuario'=>1, 
                'idCentro'=>9, 
                'representacion'=>2
            ],
        ];

        foreach($miembrosGobierno as $m){
            $miembro = new MiembroGobierno();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idCentro = $m['idCentro'];
            $miembro->fechaTomaPosesion = now();
            $miembro->fechaCese = null;
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->estado = true;
            $miembro->save();
        }
    }
}
