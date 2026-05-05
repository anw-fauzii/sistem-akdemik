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
        Schema::create('kedisiplinan_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anggota_kelas_id');
            $table->unsignedBigInteger('kedisiplinan_poin_id');

            $table->foreign('anggota_kelas_id')
                ->references('id')
                ->on('anggota_kelas')
                ->cascadeOnDelete();
                
            $table->foreign('kedisiplinan_poin_id')
                ->references('id')
                ->on('kedisiplinan_poin')
                ->restrictOnDelete();

            $table->date('tanggal_kejadian'); 
            $table->text('keterangan')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kedisiplinan_siswa');
    }
};
