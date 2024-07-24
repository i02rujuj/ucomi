<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoConvocatoria;

class TipoConvocatoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Ordinaria';
        $tipo->id = 1;
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Extraordinaria';
        $tipo->id = 2;
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Urgente';
        $tipo->id = 3;
        $tipo->save();
    }
}
