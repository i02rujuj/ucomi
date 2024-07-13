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
        Schema::create('convocados', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('idConvocatoria');
            $table->unsignedBigInteger('idUsuario');
            $table->integer('asiste')->default(0);
            $table->integer('notificado')->default(0);
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('idConvocatoria')->references('id')->on('convocatorias');
            $table->foreign('idUsuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocados');
    }
};
