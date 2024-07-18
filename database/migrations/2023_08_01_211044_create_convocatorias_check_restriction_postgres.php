<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(env('DB_CONNECTION')=="pgsql"){
            // Restricción para poner a null idComision si idJunta tiene valor o viceversa
            // No pueden estar rellenas las dos
            //DB::statement("ALTER TABLE convocatorias ADD CONSTRAINT chk_idJunta_idComision_convocatoria CHECK (('idJunta' IS NULL AND 'idComision' IS NOT NULL) OR ('idJunta' IS NOT NULL AND 'idComision' IS NULL))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(env('DB_CONNECTION')=="pgsql"){
            //DB::statement('ALTER TABLE convocatorias DROP CONSTRAINT chk_idJunta_idComision_convocatoria;');
        }
    }
};
