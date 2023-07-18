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
        $ciencias = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Ciencias',
            'direccion' => 'Campus de Rabanales',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $educacion = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Ciencias de la Eduación y Psicología',
            'direccion' => 'Campus de Menéndez Pidal',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $trabajo = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Ciencias del trabajo',
            'direccion' => 'Campus del Centro Histórico',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $derecho = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Derecho y Ciencias Económicas y Empresariales',
            'direccion' => 'Campus del Centro Histórico',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $filosofia = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Filosofía y Letras',
            'direccion' => 'Campus del Centro Histórico',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $medicina = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Medicina y Enfermería',
            'direccion' => 'Campus de Menéndez Pidal',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $veterinaria = Centro::factory(Centro::class)->create([
            'nombre' => 'Facultad de Veterinaria',
            'direccion' => 'Campus de Rabanales',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $epsBelmez = Centro::factory(Centro::class)->create([
            'nombre' => 'Escuela Politécnica Superior de Belmez',
            'direccion' => 'Campus de Belmez',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $epsUCO = Centro::factory(Centro::class)->create([
            'nombre' => 'Escuela Politécnica Superior de Córdoba',
            'direccion' => 'Campus de Rabanales',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $agronomica = Centro::factory(Centro::class)->create([
            'nombre' => 'Escuela Técnica Superior de Ingeniería Agronómica y de Montes',
            'direccion' => 'Campus de Rabanales',
            'tipo' => 'propio',
            'estado' => true
        ]);

        $sagrado = Centro::factory(Centro::class)->create([
            'nombre' => 'Centro de Magisterio Sagrado Corazón',
            'direccion' => 'Córdoba',
            'tipo' => 'adscrito',
            'estado' => true
        ]);

        $fidisec = Centro::factory(Centro::class)->create([
            'nombre' => 'Centro Universitario FIDISEC',
            'direccion' => 'Cabra (Córdoba)',
            'tipo' => 'adscrito',
            'estado' => true
        ]);

        $idep = Centro::factory(Centro::class)->create([
            'nombre' => 'Instituto de Estudios de Posgrado',
            'direccion' => 'Córdoba',
            'tipo' => '',
            'estado' => true
        ]);

        $santisteban = Centro::factory(Centro::class)->create([
            'nombre' => 'Centro Intergeneracional Francisco de Santisteban',
            'direccion' => 'Córdoba',
            'tipo' => '',
            'estado' => true
        ]);
    }
}
