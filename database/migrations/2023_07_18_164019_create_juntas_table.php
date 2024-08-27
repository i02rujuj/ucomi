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
        Schema::create('juntas', function (Blueprint $table) {
            $table->id()->startingValue(10000);
            $table->unsignedBigInteger('idCentro');
            $table->date('fechaConstitucion');
            $table->date('fechaDisolucion')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idCentro')->references('id')->on('centros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juntas');
    }
};
