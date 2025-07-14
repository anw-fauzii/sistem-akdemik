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
        Schema::create('anggota_jemputan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anggota_kelas_id')->unsigned();
            $table->unsignedBigInteger('jemputan_id')->unsigned();
            $table->enum('keterangan', ['PP', 'Pulang', 'Pergi']);
            $table->boolean('diskon')->nullable();
            $table->timestamps();

            $table->foreign('anggota_kelas_id')->references('id')->on('anggota_kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jemputan_id')->references('id')->on('jemputan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_jemputan');
    }
};
