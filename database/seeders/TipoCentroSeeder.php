<?php

namespace Database\Seeders;

use App\Models\TipoCentro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoCentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo = new TipoCentro();
        $tipo->nombre = 'Facultad';
        $tipo->estado = true;
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Escuela';
        $tipo->estado = true;
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Otro';
        $tipo->estado = true;
        $tipo->save();
    }
}
