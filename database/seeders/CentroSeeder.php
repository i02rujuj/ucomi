<?php

namespace Database\Seeders;

use App\Models\Centro;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciencias = new Centro();
        $ciencias->nombre = 'Facultad de Ciencias';
        $ciencias->direccion = 'Campus de Rabanales';
        $ciencias->idTipo = 1;
        $ciencias->estado = true;
        $ciencias->save();

        $educacion = new Centro();
        $educacion->nombre = 'Facultad de Ciencias de la Eduación y Psicología';
        $educacion->direccion = 'Campus de Menéndez Pidal';
        $educacion->idTipo = 1;
        $educacion->estado = true;
        $educacion->save();

        $trabajo = new Centro();
        $trabajo->nombre = 'Facultad de Ciencias del trabajo';
        $trabajo->direccion = 'Campus del Centro Histórico';
        $trabajo->idTipo = 1;
        $trabajo->estado = true;
        $trabajo->save();

        $derecho = new Centro();
        $derecho->nombre = 'Facultad de Derecho y Ciencias Económicas y Empresariales';
        $derecho->direccion = 'Campus del Centro Histórico';
        $derecho->idTipo = 1;
        $derecho->estado = true;
        $derecho->save();

        $filosofia = new Centro();
        $filosofia->nombre = 'Facultad de Filosofía y Letras';
        $filosofia->direccion = 'Campus del Centro Histórico';
        $filosofia->idTipo = 1;
        $filosofia->estado = true;
        $filosofia->save();

        $medicina = new Centro();
        $medicina->nombre = 'Facultad de Medicina y Enfermería';
        $medicina->direccion = 'Campus de Menéndez Pidal';
        $medicina->idTipo = 1;
        $medicina->estado = true;
        $medicina->save();

        $veterinaria = new Centro();
        $veterinaria->nombre = 'Facultad de Veterinaria';
        $veterinaria->direccion = 'Campus de Rabanales';
        $veterinaria->idTipo = 1;
        $veterinaria->estado = true;
        $veterinaria->save();

        $epsBelmez = new Centro();
        $epsBelmez->nombre = 'Escuela Politécnica Superior de Belmez';
        $epsBelmez->direccion = 'Campus de Belmez';
        $epsBelmez->idTipo = 2;
        $epsBelmez->estado = true;
        $epsBelmez->save();

        $epsUCO = new Centro();
        $epsUCO->nombre = 'Escuela Politécnica Superior de Córdoba';
        $epsUCO->direccion = 'Campus de Rabanales';
        $epsUCO->idTipo = 2;
        $epsUCO->estado = true;
        $epsUCO->save();

        $agronomica = new Centro();
        $agronomica->nombre = 'Escuela Técnica Superior de Ingeniería Agronómica y de Montes';
        $agronomica->direccion = 'Campus de Rabanales';
        $agronomica->idTipo = 2;
        $agronomica->estado = true;
        $agronomica->save();

        $sagrado = new Centro();
        $sagrado->nombre = 'Centro de Magisterio Sagrado Corazón';
        $sagrado->direccion = 'Córdoba';
        $sagrado->idTipo = 3;
        $sagrado->estado = true;
        $sagrado->save();

        $fidisec = new Centro();
        $fidisec->nombre = 'Centro Universitario FIDISEC';
        $fidisec->direccion = 'Cabra (Córdoba)';
        $fidisec->idTipo = 3;
        $fidisec->estado = true;
        $fidisec->save();

        $idep = new Centro();
        $idep->nombre = 'Instituto de Estudios de Posgrado';
        $idep->direccion = 'Córdoba';
        $idep->idTipo = 3;
        $idep->estado = true;
        $idep->save();

        $santisteban = new Centro();
        $santisteban->nombre = 'Centro Intergeneracional Francisco de Santisteban';
        $santisteban->direccion = 'Córdoba';
        $santisteban->idTipo = 3;
        $santisteban->estado = true;
        $santisteban->save();

    }
}
