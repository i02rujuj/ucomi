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
        $ciencias->tipo = "propio";
        $ciencias->estado = true;
        $ciencias->save();

        $educacion = new Centro();
        $educacion->nombre = 'Facultad de Ciencias de la Eduación y Psicología';
        $educacion->direccion = 'Campus de Menéndez Pidal';
        $educacion->tipo = "propio";
        $educacion->estado = true;
        $educacion->save();

        $trabajo = new Centro();
        $trabajo->nombre = 'Facultad de Ciencias del trabajo';
        $trabajo->direccion = 'Campus del Centro Histórico';
        $trabajo->tipo = "propio";
        $trabajo->estado = true;
        $trabajo->save();

        $derecho = new Centro();
        $derecho->nombre = 'Facultad de Derecho y Ciencias Económicas y Empresariales';
        $derecho->direccion = 'Campus del Centro Histórico';
        $derecho->tipo = "propio";
        $derecho->estado = true;
        $derecho->save();

        $filosofia = new Centro();
        $filosofia->nombre = 'Facultad de Filosofía y Letras';
        $filosofia->direccion = 'Campus del Centro Histórico';
        $filosofia->tipo = "propio";
        $filosofia->estado = true;
        $filosofia->save();

        $medicina = new Centro();
        $medicina->nombre = 'Facultad de Medicina y Enfermería';
        $medicina->direccion = 'Campus de Menéndez Pidal';
        $medicina->tipo = "propio";
        $medicina->estado = true;
        $medicina->save();

        $veterinaria = new Centro();
        $veterinaria->nombre = 'Facultad de Veterinaria';
        $veterinaria->direccion = 'Campus de Rabanales';
        $veterinaria->tipo = "propio";
        $veterinaria->estado = true;
        $veterinaria->save();

        $epsBelmez = new Centro();
        $epsBelmez->nombre = 'Escuela Politécnica Superior de Belmez';
        $epsBelmez->direccion = 'Campus de Belmez';
        $epsBelmez->tipo = "propio";
        $epsBelmez->estado = true;
        $epsBelmez->save();

        $epsUCO = new Centro();
        $epsUCO->nombre = 'Escuela Politécnica Superior de Córdoba';
        $epsUCO->direccion = 'Campus de Rabanales';
        $epsUCO->tipo = "propio";
        $epsUCO->estado = true;
        $epsUCO->save();

        $agronomica = new Centro();
        $agronomica->nombre = 'Escuela Técnica Superior de Ingeniería Agronómica y de Montes';
        $agronomica->direccion = 'Campus de Rabanales';
        $agronomica->tipo = "propio";
        $agronomica->estado = true;
        $agronomica->save();

        $sagrado = new Centro();
        $sagrado->nombre = 'Centro de Magisterio Sagrado Corazón';
        $sagrado->direccion = 'Córdoba';
        $sagrado->tipo = "adscrito";
        $sagrado->estado = true;
        $sagrado->save();

        $fidisec = new Centro();
        $fidisec->nombre = 'Centro Universitario FIDISEC';
        $fidisec->direccion = 'Cabra (Córdoba)';
        $fidisec->tipo = "adscrito";
        $fidisec->estado = true;
        $fidisec->save();

        $idep = new Centro();
        $idep->nombre = 'Instituto de Estudios de Posgrado';
        $idep->direccion = 'Córdoba';
        $idep->tipo = "adscrito";
        $idep->estado = true;
        $idep->save();

        $santisteban = new Centro();
        $santisteban->nombre = 'Centro Intergeneracional Francisco de Santisteban';
        $santisteban->direccion = 'Córdoba';
        $santisteban->tipo = "adscrito";
        $santisteban->estado = true;
        $santisteban->save();

    }
}
