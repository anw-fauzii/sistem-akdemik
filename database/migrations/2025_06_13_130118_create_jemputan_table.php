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
        Schema::create('jemputan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->string('driver');
            $table->string('harga_pp');
            $table->string('harga_setengah');
            $table->timestamps();
            
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemputan');
    }
};
