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
        $representacion->nombre = 'Director/a';
        $representacion->deCentro = 1;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 2;
        $representacion->nombre = 'Decano/a';
        $representacion->deCentro = 1;
        $representacion->deJunta = 1;
        $representacion->deComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 3;
        $representacion->nombre = 'Secretario/a';
        $representacion->deCentro = 1;
        $representacion->deJunta = 1;
        $representacion->deComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 4;
        $representacion->nombre = 'Subdirector/a';
        $representacion->deCentro = 1;
        $representacion->deJunta = 0;
        $representacion->deComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 5;
        $representacion->nombre = 'Vicedecano/a';
        $representacion->deCentro = 1;
        $representacion->deJunta = 0;
        $representacion->deComision = 0;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 6;
        $representacion->nombre = 'Profesorado vinculación permanente';
        $representacion->deCentro = 0;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 7;
        $representacion->nombre = 'Personal Docente e Investigador';
        $representacion->deCentro = 0;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 8;
        $representacion->nombre = 'Personal de Administración y Servicios';
        $representacion->deCentro = 0;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 9;
        $representacion->nombre = 'Estudiantes';
        $representacion->deCentro = 0;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();

        $representacion = new Representacion();
        $representacion->id = 10;
        $representacion->nombre = 'Libre designación';
        $representacion->deCentro = 0;
        $representacion->deJunta = 1;
        $representacion->deComision = 1;
        $representacion->save();
    }
}
