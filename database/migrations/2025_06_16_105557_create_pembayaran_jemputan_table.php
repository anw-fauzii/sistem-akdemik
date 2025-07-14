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
        Schema::create('pembayaran_jemputan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anggota_jemputan_id');
            $table->unsignedBigInteger('bulan_spp_id');
            $table->integer('jumlah_bayar');
            $table->timestamps();

            $table->foreign('anggota_jemputan_id')->references('id')->on('anggota_jemputan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('bulan_spp_id')->references('id')->on('bulan_spp')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_jemputan');
    }
};
