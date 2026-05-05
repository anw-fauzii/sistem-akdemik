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
        Schema::create('kedisiplinan_poin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_aturan', 150);
            $table->enum('kategori', ['pelanggaran', 'prestasi'])->index();
            $table->enum('tingkat', ['ringan', 'sedang', 'berat'])->index();
            $table->integer('poin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kedisiplinan_poin');
    }
};
