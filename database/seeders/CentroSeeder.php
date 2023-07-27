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
        $centros =[
            [
                'nombre'=>'Facultad de Ciencias', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Ciencias de la Eduación y Psicología', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Ciencias del trabajo', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Derecho y Ciencias Económicas y Empresariales', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Filosofía y Letras', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Medicina y Enfermería', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Facultad de Veterinaria', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>1
            ],
            [
                'nombre'=>'Escuela Politécnica Superior de Belmez', 
                'direccion'=>'Campus de Belmez', 
                'tipo'=>2
            ],
            [
                'nombre'=>'Escuela Politécnica Superior de Córdoba', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>2
            ],
            [
                'nombre'=>'Escuela Técnica Superior de Ingeniería Agronómica y de Montes', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>2
            ],
            [
                'nombre'=>'Centro de Magisterio Sagrado Corazón', 
                'direccion'=>'Córdoba', 
                'tipo'=>3
            ],
            [
                'nombre'=>'Centro Universitario FIDISEC', 
                'direccion'=>'Cabra (Córdoba)', 
                'tipo'=>3
            ],
            [
                'nombre'=>'Instituto de Estudios de Posgrado', 
                'direccion'=>'Córdoba', 
                'tipo'=>3
            ],
            [
                'nombre'=>'Centro Intergeneracional Francisco de Santisteban', 
                'direccion'=>'Córdoba', 
                'tipo'=>3
            ]
        ];

        foreach($centros as $c){
            $centro = new Centro();
            $centro->nombre = $c['nombre'];
            $centro->direccion = $c['direccion'];
            $centro->idTipo = $c['tipo'];
            $centro->estado = true;
            $centro->save();
        }
    }
}
