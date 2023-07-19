<?php

namespace Database\Seeders;

use App\Models\MiembroGobierno;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MiembroGobiernoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MiembroGobierno::factory()->count(5)->create();
    }
}
