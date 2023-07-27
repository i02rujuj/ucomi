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
            $table->id();
            $table->unsignedBigInteger('idJunta')->unique;
            $table->unsignedBigInteger('idUsuario')->unique;
            $table->date('fechaTomaPosesion');
            $table->date('fechaCese')->nullable();
            $table->unsignedBigInteger('idRepresentacion');
            $table->boolean('estado');
            $table->timestamps();

            $table->foreign('idJunta')->references('id')->on('juntas');
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idRepresentacion')->references('id')->on('representaciones_general');
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
