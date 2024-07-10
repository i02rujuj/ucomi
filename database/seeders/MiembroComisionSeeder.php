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

        $miembrosComision =[
            [
                'idUsuario' => 5, 
                'idComision' => 1, 
                'representacion' => 4,
                'responsable' => 1
            ],
            [
                'idUsuario' => 6, 
                'idComision' => 1, 
                'representacion' => 5,
                'responsable' => 1
            ],
        ];

        foreach($miembrosComision as $m){
            $miembro = new MiembroComision();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idComision = $m['idComision'];
            $miembro->fechaTomaPosesion = now();
            $miembro->fechaCese = null;
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->responsable = $m['responsable'];
            $miembro->save();
        }
        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        for($i=0; $i<10; $i++){
            MiembroComision::factory()->count(1)->create();
        }
    }
}
