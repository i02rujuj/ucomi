<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RepresentacionGeneral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RepresentacionGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $representacion = new RepresentacionGeneral();
        $representacion->nombre = 'Profesorado vinculación permanente';
        $representacion->estado = true;
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->nombre = 'Otro personal docente e investigador';
        $representacion->estado = true;
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->nombre = 'PAS';
        $representacion->estado = true;
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->nombre = 'Alumnado';
        $representacion->estado = true;
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->nombre = 'Personal libre designación';
        $representacion->estado = true;
        $representacion->save();
    }
}
