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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_ajaran_id')->unsigned();
            $table->string('guru_nipy');
            $table->string('pendamping_nipy');
            $table->string('tingkatan_kelas', 2);
            $table->string('nama_kelas', 30);
            $table->string('romawi', 30);
            $table->integer('spp');
            $table->integer('biaya_makan');
            $table->timestamps();

            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('guru_nipy')->references('nipy')->on('guru')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pendamping_nipy')->references('nipy')->on('guru')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
