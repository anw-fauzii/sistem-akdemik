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
        Schema::create('ekstrakurikuler', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->string('guru_nipy');
            $table->string('nama_ekstrakurikuler', 30);
            $table->integer('biaya')->default('0');
            $table->timestamps();

            $table->foreign('guru_nipy')->references('nipy')->on('guru')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler');
    }
};
