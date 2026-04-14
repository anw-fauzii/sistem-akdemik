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
        Schema::create('pesan_saran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('siswa_nis');
            $table->string('subjek')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->foreign('siswa_nis')->references('nis')->on('siswa')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_saran');
    }
};
