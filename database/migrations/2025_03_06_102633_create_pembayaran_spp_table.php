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
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anggota_kelas_id');
            $table->unsignedBigInteger('bulan_spp_id');
            $table->integer('nominal_spp');
            $table->integer('biaya_makan');
            $table->integer('total_pembayaran');
            $table->string('keterangan');
            $table->timestamps();
            $table->foreign('anggota_kelas_id')->references('id')->on('anggota_kelas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
