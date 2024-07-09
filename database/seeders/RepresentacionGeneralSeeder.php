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
        $representacion->id = 1;
        $representacion->nombre = 'Director/a | Decano/a';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 2;
        $representacion->nombre = 'Secretario/a';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 3;
        $representacion->nombre = 'Profesorado vinculación permanente';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 4;
        $representacion->nombre = 'Otro personal docente e investigador';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 5;
        $representacion->nombre = 'PAS';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 6;
        $representacion->nombre = 'Alumnado';
        $representacion->save();

        $representacion = new RepresentacionGeneral();
        $representacion->id = 7;
        $representacion->nombre = 'Personal libre designación';
        $representacion->save();
    }
}
