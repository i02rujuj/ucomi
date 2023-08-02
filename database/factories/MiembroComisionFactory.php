<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Comision;
use Illuminate\Support\Facades\DB;
use App\Models\RepresentacionGeneral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MiembroJunta>
 */
class MiembroComisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Obetenemos usuario que exista como miembro de la misma junta (Politécnica Superior Córdoba -> 1) y que no se repitan los usuarios mientras se vayan introduciendo
        $usuario = $this->faker->randomElement(User::query()
                ->join('miembros_junta', 'miembros_junta.idUsuario', '=', 'users.id')
                ->where('miembros_junta.idJunta', 1)
                ->leftjoin('miembros_comision', 'miembros_comision.idUsuario', '=', 'users.id')
                ->where('miembros_comision.id', null)
                ->select('users.id', 'miembros_junta.idRepresentacion')
                ->get('users.id', 'miembros_junta.idRepresentacion'));

        return [
            'idComision' => $this->faker->randomElement(Comision::all()),
            'idUsuario' => $usuario['id'],
            'fechaTomaPosesion' => now(),
            'fechaCese' => null,
            'idRepresentacion' => $usuario['idRepresentacion'],
            'estado' => 1
        ];
    }
}
