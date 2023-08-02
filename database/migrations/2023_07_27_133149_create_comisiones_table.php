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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->date('fechaConstitucion');
            $table->date('fechaDisolucion')->nullable();
            $table->unsignedBigInteger('idJunta');
            $table->boolean('estado');
            $table->timestamps();

            $table->foreign('idJunta')->references('id')->on('juntas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
