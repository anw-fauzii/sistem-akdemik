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
        Schema::create('presensi_ekstrakurikuler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anggota_ekstrakurikuler_id');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'alpha', 'izin']);
            $table->timestamps();
            $table->foreign('anggota_ekstrakurikuler_id')->references('id')->on('anggota_ekstrakurikuler')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presesnsi_ekstrakurikuler');
    }
};
