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
                'nombre'=>'Ciencias', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=> 1,
                'logo' => asset('img/centros/logo_ciencias.png')
            ],
            [
                'nombre'=>'Ciencias de la Educación y Psicología', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_ciencias_educación.png')
            ],
            [
                'nombre'=>'Ciencias del trabajo', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_ciencias_trabajo.png')
            ],
            [
                'nombre'=>'Derecho y Ciencias Económicas y Empresariales', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_derecho.jpg')
            ],
            [
                'nombre'=>'Filosofía y Letras', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_filosofía.png')
            ],
            [
                'nombre'=>'Medicina y Enfermería', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_medicina.png')
            ],
            [
                'nombre'=>'Veterinaria', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>1,
                'logo' => asset('img/centros/logo_veterinaria.png')
            ],
            [
                'nombre'=>'Politécnica Superior de Belmez', 
                'direccion'=>'Campus de Belmez', 
                'tipo'=>2,
                'logo' => asset('img/centros/logo_eps_belmez.jpeg')
            ],
            [
                'nombre'=>'Politécnica Superior de Córdoba', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>2,
                'logo' => asset('img/centros/logo_eps_uco.gif')
            ],
            [
                'nombre'=>'Técnica Superior de Ingeniería Agronómica y de Montes', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>2,
                'logo' => asset('img/centros/logo_agronomía.png')
            ],
            [
                'nombre'=>'Centro Magisterio Sagrado Corazón', 
                'direccion'=>'Córdoba', 
                'tipo'=>3,
                'logo' => asset('img/centros/logo_sagrado_corazon.png')
            ],
            [
                'nombre'=>'Centro Universitario FIDISEC', 
                'direccion'=>'Cabra (Córdoba)', 
                'tipo'=>3,
                'logo' => asset('img/centros/logo_fidisec.png')
            ],
            [
                'nombre'=>'Instituto de Estudios de Posgrado', 
                'direccion'=>'Córdoba', 
                'tipo'=>3,
                'logo' => asset('img/centros/logo_idep.jpg')
            ],
            [
                'nombre'=>'Centro Intergeneracional Francisco de Santisteban', 
                'direccion'=>'Córdoba', 
                'tipo'=>3,
                'logo' => asset('img/centros/logo_santisteban.png')
            ]
        ];

        foreach($centros as $c){
            $centro = new Centro();
            $centro->nombre = $c['nombre'];
            $centro->direccion = $c['direccion'];
            $centro->idTipo = $c['tipo'];
            $centro->logo = $c['logo'];
            $centro->save();
        }
    }
}
