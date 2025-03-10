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
        Schema::create('bulan_spp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->string('nama_bulan');
            $table->integer('bulan_angka');
            $table->integer('tambahan')->default(0);
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan_spp');
    }
};
