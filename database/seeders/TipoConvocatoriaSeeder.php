<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoConvocatoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TipoConvocatoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Ordinaria';
        $tipo->estado = true;
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Extraordinaria';
        $tipo->estado = true;
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Urgente';
        $tipo->estado = true;
        $tipo->save();
    }
}
