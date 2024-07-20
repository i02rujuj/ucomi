<?php

namespace Database\Seeders;

use App\Models\MiembroGobierno;
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
                'idUsuario' => 3, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.DIR'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 1
            ],
            [
                'idUsuario' => 4, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.SECRE'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 1
            ],
            [
                'idUsuario' => 37, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.SUBDIR'),
                'cargo' => 'Subidrección Ordenación Académica y Estudiantes',
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0
            ],
            [
                'idUsuario' => 34, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.SUBDIR'),
                'fechaTomaPosesion' => '2022-11-17',
                'cargo' => 'Subdirección Relaciones Exteriores y Movilidad',
                'responsable' => 0
            ],
            [
                'idUsuario' => 38, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.SUBDIR'),
                'fechaTomaPosesion' => '2022-11-17',
                'cargo' => 'Subdirección Calidad e Innovación',
                'responsable' => 0
            ],
            [
                'idUsuario' => 23, 
                'idCentro' => 9,
                'representacion' => config('constants.REPRESENTACIONES.GOBIERNO.LIBRE'),
                'cargo' => 'Secretaría de Dirección',
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0
            ],
        ];

        foreach($miembrosGobierno as $m){
            $miembro = new MiembroGobierno();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idCentro = $m['idCentro'];
            $miembro->idRepresentacion = $m['representacion'];
            isset($m['cargo']) ? $miembro->cargo = $m['cargo'] : null;
            $miembro->fechaTomaPosesion = $m['fechaTomaPosesion'];
            $miembro->fechaCese = null;
            $miembro->responsable = $m['responsable'];
            $miembro->save();
        }
    }
}
