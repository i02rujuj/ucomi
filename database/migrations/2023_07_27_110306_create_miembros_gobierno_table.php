<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('miembros_gobierno', function (Blueprint $table) {
            $table->id()->startingValue(10000);

            $table->unsignedBigInteger('idCentro');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idRepresentacion');
            $table->string('cargo')->nullable();
            $table->date('fechaTomaPosesion');
            $table->date('fechaCese')->nullable();
            $table->integer('responsable')->default(0);;
            $table->timestamps();
            $table->softDeletes();
            $table->string('vigente'); // Mantenida por trigger
            $table->string('activo'); // Mantenida por trigger

            $table->foreign('idCentro')->references('id')->on('centros');
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idRepresentacion')->references('id')->on('representaciones');

            // No permitir duplicados en la combinación de los siguientes campos y que tampoco sean null cada campo (Simular clave primaria compuesta en laravel)
            // Solamente podrá haber un usuario relacionado con una determinado centro vigente y activo 
            $table->unique(['idCentro', 'idUsuario', 'vigente', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_gobierno');
    }
};
