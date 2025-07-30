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
        Schema::create('kesehatan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anggota_kelas_id');
            $table->unsignedBigInteger('bulan_spp_id');
            $table->string('tb')->nullable();
            $table->string('bb')->nullable();
            $table->string('lila')->nullable();
            $table->string('lika')->nullable();
            $table->string('lp')->nullable();
            $table->string('mata')->nullable();
            $table->string('telinga')->nullable();
            $table->string('gigi')->nullable();
            $table->string('tensi')->nullable();
            $table->string('hasil')->nullable();
            $table->timestamps();

            $table->foreign('anggota_kelas_id')->references('id')->on('anggota_kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('bulan_spp_id')->references('id')->on('bulan_spp')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesehatan');
    }
};
