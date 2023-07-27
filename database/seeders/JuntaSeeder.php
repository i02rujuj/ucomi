<?php

namespace Database\Seeders;

use App\Models\Junta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JuntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Junta::factory()->count(10)->create();
    }
}
