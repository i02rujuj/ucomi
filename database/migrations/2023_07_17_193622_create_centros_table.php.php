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
        Schema::create('centros', function (Blueprint $table) {
            // Es lo mismo más simplificado a continuación: $table->bigIncrements('id')->unique();
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->unsignedBigInteger('idTipo');
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idTipo')->references('id')->on('tipos_centro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centros');

    }
};
