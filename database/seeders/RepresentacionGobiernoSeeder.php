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
        $director->id=1;
        $director->nombre = 'Director/a | Decano/a';
        $director->save();

        $secretario = new RepresentacionGobierno();
        $secretario->id=2;
        $secretario->nombre = 'Secretario/a';
        $secretario->save();

        $vicedirector = new RepresentacionGobierno();
        $vicedirector->id=3;
        $vicedirector->nombre = 'ViceDirector/a | ViceDecano/a';
        $vicedirector->save();

        $subdirector = new RepresentacionGobierno();
        $subdirector->id=4;
        $subdirector->nombre = 'Subdirector/a';
        $subdirector->save();

        $secretarioDirec = new RepresentacionGobierno();
        $secretarioDirec->id=5;
        $secretarioDirec->nombre = 'Secretario/a de dirección';
        $secretarioDirec->save();
    }
}
