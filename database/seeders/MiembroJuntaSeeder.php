<?php

namespace Database\Seeders;

use App\Models\MiembroJunta;
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
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.DIR'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 1
            ],
            [
                'idUsuario' => 4, 
                'idJunta' => 1,
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.SECRE'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 1
            ],
            [
                'idUsuario' => 5, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 6, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 7, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 8, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 9, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 10, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 11, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 12, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 13, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 14, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 15, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 16, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 17, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 18, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI_VP'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 19, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 20, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 21, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 22, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PDI'),
                'fechaTomaPosesion' => '2022-11-17',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 23, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PAS'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 24, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.PAS'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 25, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 26, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 27, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 28, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 29, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 30, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 31, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.EST'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 32, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-10-31',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 33, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 34, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 35, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 36, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 37, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 38, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 39, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 40, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
            [
                'idUsuario' => 41, 
                'idJunta' => 1, 
                'representacion' => config('constants.REPRESENTACIONES.JUNTA.LIBRE'),
                'fechaTomaPosesion' => '2022-12-02',
                'responsable' => 0,
            ],
        ];

        foreach($miembrosJunta as $m){
            $miembro = new MiembroJunta();
            $miembro->idUsuario = $m['idUsuario'];
            $miembro->idJunta = $m['idJunta'];
            $miembro->idRepresentacion = $m['representacion'];
            $miembro->fechaTomaPosesion = $m['fechaTomaPosesion'];
            $miembro->fechaCese = null;
            $miembro->responsable = $m['responsable'];
            $miembro->save();
        }

        // Se hace así para que haga un commit en cada creación y permita no repetir miembros
        /*for($i=0; $i<10; $i++){
            MiembroJunta::factory()->count(1)->create();
        }*/
    }
}
