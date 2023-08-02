<?php

namespace Database\Seeders;

use App\Models\Comision;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comisiones =[
            [
                'nombre' => 'Asuntos económicos', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Docencia', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Prácticas externas', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Relaciones internacionales', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Reconocimiento y transferencia', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'TFG Grado Ingeniería informática', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Planes estudios Grado Ingeniería eléctrica', 
                'descripcion' => 'Todas las comisiones son presididas por el Sr. Director o el Subdirector en quien delegue.', 
                'fechaConstitucion' => now(),
                'fechaDisolucion' => null,
                'idJunta' => 1,
                'estado' => 1,
            ],
            
        ];

        foreach($comisiones as $com){
            $c = new Comision();
            $c->nombre = $com['nombre'];
            $c->descripcion = $com['descripcion'];
            $c->fechaConstitucion = $com['fechaConstitucion'];
            $c->fechaDisolucion = $com['fechaDisolucion'];
            $c->idJunta = $com['idJunta'];
            $c->estado = $com['estado'];
            $c->save();
        }
    }
}
