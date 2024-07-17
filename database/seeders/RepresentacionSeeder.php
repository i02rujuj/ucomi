<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Representacion;

class RepresentacionSeeder extends Seeder
{
    public function run(): void
    {
        $representacion = new Representacion();
        $representacion->id = 1;
        $representacion->nombre = 'Director/a | Decano/a';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 2;
        $representacion->nombre = 'Secretario/a';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 3;
        $representacion->nombre = 'Profesorado vinculación permanente';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 4;
        $representacion->nombre = 'Personal Docente e Investigador';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 5;
        $representacion->nombre = 'Personal de Administración y Servicios';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 6;
        $representacion->nombre = 'Estudiantes';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 7;
        $representacion->nombre = 'Designado por el Director/a';
        $representacion->tipoJunta = 1;
        $representacion->tipoComision = 0;
        $representacion->save();
    }
}
