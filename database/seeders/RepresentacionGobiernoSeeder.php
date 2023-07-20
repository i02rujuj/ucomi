<?php

namespace Database\Seeders;

use App\Models\RepresentacionGobierno;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepresentacionGobiernoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $director = new RepresentacionGobierno();
        $director->nombre = 'Director/a | Decano/a';
        $director->estado = true;
        $director->save();

        $secretario = new RepresentacionGobierno();
        $secretario->nombre = 'Secretario/a';
        $secretario->estado = true;
        $secretario->save();

        $vicedirector = new RepresentacionGobierno();
        $vicedirector->nombre = 'ViceDirector/a | ViceDecano/a';
        $vicedirector->estado = true;
        $vicedirector->save();

        $subdirector = new RepresentacionGobierno();
        $subdirector->nombre = 'Subdirector/a';
        $subdirector->estado = true;
        $subdirector->save();

        $secretarioDirec = new RepresentacionGobierno();
        $secretarioDirec->nombre = 'Secretario/a de dirección';
        $secretarioDirec->estado = true;
        $secretarioDirec->save();
    }
}
