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
        Schema::create('tarif_spp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit');
            $table->year('tahun_masuk');
            $table->integer('spp');
            $table->integer('biaya_makan');
            $table->integer('snack');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_spp');
    }
};
