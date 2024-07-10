<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\RepresentacionGeneral;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MiembroJunta>
 */
class MiembroJuntaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idUsuario' => $this->faker->randomElement(User::query()
                ->leftjoin('miembros_gobierno', 'miembros_gobierno.idUsuario', '=', 'users.id')
                ->where('miembros_gobierno.id', null)
                ->leftjoin('miembros_junta', 'miembros_junta.idUsuario', '=', 'users.id')
                ->where('miembros_junta.id', null)
                ->get('users.id')),
            'idJunta' => 1,
            'fechaTomaPosesion' => now(),
            'fechaCese' => null,
            'idRepresentacion' => $this->faker->randomElement([3,4,5,6]),
        ];
    }
}
