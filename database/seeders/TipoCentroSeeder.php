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
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Escuela';
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Otro';
        $tipo->save();
    }
}
