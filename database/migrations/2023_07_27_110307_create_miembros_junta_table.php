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
        Schema::create('miembros_junta', function (Blueprint $table) {
            $table->id()->startingValue(10000);
            $table->unsignedBigInteger('idJunta');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idRepresentacion');
            $table->date('fechaTomaPosesion');
            $table->date('fechaCese')->nullable();
            $table->integer('responsable')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('vigente'); // Mantenida por trigger
            $table->string('activo'); // Mantenida por trigger

            $table->foreign('idJunta')->references('id')->on('juntas');
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idRepresentacion')->references('id')->on('representaciones');

            // Simular clave primaria compuesta en laravel: No permitir duplicados en la combinación de los siguientes campos y que tampoco sean null cada campo
            // Solamente podrá haber un usuario relacionado con una determinada junta vigente y activa 
            $table->unique(['idJunta', 'idUsuario', 'vigente', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_junta');
    }
};
