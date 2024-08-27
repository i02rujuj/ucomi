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
        Schema::create('representaciones', function (Blueprint $table) {
                $table->id()->startingValue(10000);
                $table->string('nombre');
                $table->integer('deCentro')->default(0);
                $table->integer('deJunta')->default(0);
                $table->integer('deComision')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representaciones');
    }
};
