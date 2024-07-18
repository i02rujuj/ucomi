<?php

namespace Database\Seeders;

use App\Models\Comision;
use Illuminate\Database\Seeder;

class ComisionSeeder extends Seeder
{
    public function run(): void
    {
        $comisiones =[
            [
                'id' => 1,
                'nombre' => 'Asuntos económicos', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 2,
                'nombre' => 'Docencia', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 3,
                'nombre' => 'Prácticas externas', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 4,
                'nombre' => 'Relaciones internacionales', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 5,
                'nombre' => 'Reconocimiento y transferencia', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 6,
                'nombre' => 'TFG Grado Ingeniería Eléctrica', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 7,
                'nombre' => 'TFG Grado Ingeniería Electrónica Industrial', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 8,
                'nombre' => 'TFG Grado Ingeniería Mecánica', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 9,
                'nombre' => 'TFG Grado Ingeniería Informática', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 10,
                'nombre' => 'Trabajo Fin de Máster', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 11,
                'nombre' => 'Planes de estudio de grado en Ingeniería Eléctrica', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 12,
                'nombre' => 'Planes de estudio de grado en Ingeniería Electrónica Industrial', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 13,
                'nombre' => 'Planes estudios Grado Ingeniería Mecánica', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 14,
                'nombre' => 'Planes estudios Grado Ingeniería Informática', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            [
                'id' => 15,
                'nombre' => 'Planes estudios Máster Universitario en Ingerniería Industrial', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => '2023-01-09',
                'fechaDisolucion' => null,
                'idJunta' => 1,
            ],
            
        ];

        foreach($comisiones as $com){
            $c = new Comision();
            $c->nombre = $com['nombre'];
            $c->descripcion = $com['descripcion'];
            $c->fechaConstitucion = $com['fechaConstitucion'];
            $c->fechaDisolucion = $com['fechaDisolucion'];
            $c->idJunta = $com['idJunta'];
            $c->save();
        }
    }
}
