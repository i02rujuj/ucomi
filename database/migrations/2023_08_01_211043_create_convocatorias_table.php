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
        Schema::create('convocatorias', function (Blueprint $table) {
            $table->id();
            $table->string('lugar');
            $table->date('fecha');
            $table->time('hora');
            $table->unsignedBigInteger('idTipo');
            $table->unsignedBigInteger('idComision')->nullable();
            $table->unsignedBigInteger('idJunta')->nullable();
            $table->string('acta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idComision')->references('id')->on('comisiones');
            $table->foreign('idJunta')->references('id')->on('juntas');
            $table->foreign('idTipo')->references('id')->on('tipos_convocatoria');
        });
  
        // Restricción para poner a null idComision si idJunta tiene valor o viceversa
        // No pueden estar rellenas las dos
        DB::statement('ALTER TABLE convocatorias ADD CONSTRAINT chk_idJuntaNull_idComisionNull_convocatoria CHECK (idJunta is NULL or idComision is NULL);');
        DB::statement('ALTER TABLE convocatorias ADD CONSTRAINT chk_idJuntaNotNull_idComisionNotNull_convocatoria CHECK (idJunta is not NULL or idComision is not NULL);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatorias');
    }
};
