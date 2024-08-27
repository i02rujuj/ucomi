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
            $table->id()->startingValue(10000);
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->date('fechaConstitucion');
            $table->date('fechaDisolucion')->nullable();
            $table->unsignedBigInteger('idJunta');
            $table->timestamps();
            $table->softDeletes();

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
