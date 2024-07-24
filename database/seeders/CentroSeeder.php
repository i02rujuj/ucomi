<?php

namespace Database\Seeders;

use App\Models\Centro;
use Illuminate\Database\Seeder;

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centros = collect([
            [
                'id' => 1,
                'nombre'=>'Ciencias', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=> config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_ciencias.png')
            ],
            [
                'id' => 2,
                'nombre'=>'Ciencias de la Educación y Psicología', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_ciencias_educación.png')
            ],
            [
                'id' => 3,
                'nombre'=>'Ciencias del trabajo', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_ciencias_trabajo.png')
            ],
            [
                'id' => 4,
                'nombre'=>'Derecho y Ciencias Económicas y Empresariales', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_derecho.jpg')
            ],
            [
                'id' => 5,
                'nombre'=>'Filosofía y Letras', 
                'direccion'=>'Campus del Centro Histórico', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_filosofía.png')
            ],
            [
                'id' => 6,
                'nombre'=>'Medicina y Enfermería', 
                'direccion'=>'Campus de Menéndez Pidal', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_medicina.png')
            ],
            [
                'id' => 7,
                'nombre'=>'Veterinaria', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>config('constants.TIPOS_CENTRO.FACULTAD'),
                'logo' => asset('img/centros/logo_veterinaria.png')
            ],
            [
                'id' => 8,
                'nombre'=>'Politécnica Superior de Belmez', 
                'direccion'=>'Campus de Belmez', 
                'tipo'=>config('constants.TIPOS_CENTRO.ESCUELA'),
                'logo' => asset('img/centros/logo_eps_belmez.jpeg')
            ],
            [
                'id' => 9,
                'nombre'=>'Politécnica Superior de Córdoba', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>config('constants.TIPOS_CENTRO.ESCUELA'),
                'logo' => asset('img/centros/logo_eps_uco.gif')
            ],
            [
                'id' => 10,
                'nombre'=>'Técnica Superior de Ingeniería Agronómica y de Montes', 
                'direccion'=>'Campus de Rabanales', 
                'tipo'=>config('constants.TIPOS_CENTRO.ESCUELA'),
                'logo' => asset('img/centros/logo_agronomía.png')
            ],
            [
                'id' => 11,
                'nombre'=>'Magisterio Sagrado Corazón', 
                'direccion'=>'Córdoba', 
                'tipo'=>config('constants.TIPOS_CENTRO.OTRO'),
                'logo' => asset('img/centros/logo_sagrado_corazon.png')
            ],
            [
                'id' => 12,
                'nombre'=>'Universitario FIDISEC', 
                'direccion'=>'Cabra (Córdoba)', 
                'tipo'=>config('constants.TIPOS_CENTRO.OTRO'),
                'logo' => asset('img/centros/logo_fidisec.png')
            ],
            [
                'id' => 13,
                'nombre'=>'Instituto de Estudios de Posgrado', 
                'direccion'=>'Córdoba', 
                'tipo'=>config('constants.TIPOS_CENTRO.OTRO'),
                'logo' => asset('img/centros/logo_idep.jpg')
            ],
            [
                'id' => 14,
                'nombre'=>'Intergeneracional Francisco de Santisteban', 
                'direccion'=>'Córdoba', 
                'tipo'=>config('constants.TIPOS_CENTRO.OTRO'),
                'logo' => asset('img/centros/logo_santisteban.png')
            ]
        ]);

        foreach($centros->reverse() as $c){
            $centro = new Centro();
            $centro->id = $c['id'];
            $centro->nombre = $c['nombre'];
            $centro->direccion = $c['direccion'];
            $centro->idTipo = $c['tipo'];
            $centro->logo = $c['logo'];
            $centro->save();
        }
    }
}
