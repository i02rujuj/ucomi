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
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Extraordinaria';
        $tipo->save();

        $tipo = new TipoConvocatoria();
        $tipo->nombre = 'Urgente';
        $tipo->save();
    }
}
