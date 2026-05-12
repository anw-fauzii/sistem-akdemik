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
        Schema::create('yaumiyah_tahfiz', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anggota_t2q_id');
            $table->unsignedBigInteger('surah_alquran_id');
            $table->unsignedBigInteger('angka_arab_id')->nullable();

            $table->foreign('anggota_t2q_id')
                ->references('id')
                ->on('anggota_t2q')
                ->cascadeOnDelete();

            $table->foreign('surah_alquran_id')
                ->references('id')
                ->on('surah_alquran')
                ->restrictOnDelete();

            $table->foreign('angka_arab_id')
                ->references('id')
                ->on('angka_arab')
                ->restrictOnDelete();

            $table->date('tanggal'); 
            $table->integer('nilai')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yaumiyah_tahfiz');
    }
};
