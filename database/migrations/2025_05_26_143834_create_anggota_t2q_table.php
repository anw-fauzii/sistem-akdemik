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
        Schema::create('anggota_t2q', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tingkat')->nullable();
            $table->unsignedBigInteger('anggota_kelas_id')->unsigned();
            $table->string('guru_nipy');
            $table->timestamps();

            $table->foreign('anggota_kelas_id')->references('id')->on('anggota_kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('guru_nipy')->references('nipy')->on('guru')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_t2q');
    }
};
