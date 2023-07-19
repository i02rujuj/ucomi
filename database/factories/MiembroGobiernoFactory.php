<?php

namespace Database\Factories;

use App\Models\Centro;
use App\Models\RepresentacionGobierno;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MiembroGobierno>
 */
class MiembroGobiernoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idCentro' => Centro::pluck('id')->random(),
            'idUsuario' => User::pluck('id')->random(),
            'fechaTomaPosesion' => now(),
            'fechaCese' => null, 
            'idRepresentacion' => RepresentacionGobierno::pluck('id')->random(),
            'estado' => true,
        ];
    }
}
