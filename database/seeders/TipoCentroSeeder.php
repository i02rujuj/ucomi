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
        $tipo->id = 1;
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Escuela';
        $tipo->id = 2;
        $tipo->save();

        $tipo = new TipoCentro();
        $tipo->nombre = 'Centro';
        $tipo->id = 3;
        $tipo->save();
    }
}
