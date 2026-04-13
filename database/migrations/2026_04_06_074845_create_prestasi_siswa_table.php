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
        Schema::create('prestasi_siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_prestasi');
            $table->enum('kategori', ['akademik', 'non_akademik']);
            $table->string('tingkat');
            $table->string('peringkat');
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('file_sertifikat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_siswa');
    }
};
