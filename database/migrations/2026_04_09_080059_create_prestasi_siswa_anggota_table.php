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
        Schema::create('prestasi_siswa_anggota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('prestasi_siswa_id')
                ->constrained('prestasi_siswa')
                ->cascadeOnDelete();
            $table->foreignId('anggota_kelas_id')
                ->constrained('anggota_kelas')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_siswa_anggota');
    }
};
