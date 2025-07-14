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
        Schema::create('anggota_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('siswa_nis');
            $table->unsignedBigInteger('kelas_id');
            $table->enum('pendaftaran', ['1', '2', '3', '4', '5']);
            $table->timestamps();

            $table->foreign('siswa_nis')->references('nis')->on('siswa')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_kelas_controller');
    }
};
