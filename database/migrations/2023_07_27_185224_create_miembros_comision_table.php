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
        Schema::create('miembros_comision', function (Blueprint $table) {
            $table->id()->startingValue(10000);
            $table->unsignedBigInteger('idComision');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idRepresentacion');
            $table->string('cargo')->nullable();
            $table->date('fechaTomaPosesion');
            $table->date('fechaCese')->nullable();
            $table->integer('responsable')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('vigente'); // Mantenida por trigger
            $table->string('activo'); // Mantenida por trigger

            $table->foreign('idComision')->references('id')->on('comisiones');
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idRepresentacion')->references('id')->on('representaciones');

            // Simular clave primaria compuesta en laravel: No permitir duplicados en la combinación de los siguientes campos y que tampoco sean null cada campo
            // Solamente podrá haber un usuario relacionado con una determinada comisión vigente y activa 
            $table->unique(['idComision', 'idUsuario', 'vigente', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_comision');
    }
};
