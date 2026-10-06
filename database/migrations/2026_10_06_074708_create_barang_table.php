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
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('nama_barang');
            $table->string('kategori_barang_id', 20);
            $table->foreign('kategori_barang_id')
                ->references('id')
                ->on('kategori_barang')
                ->restrictOnDelete();
            $table->string('lokasi_barang_id', 20);
            $table->foreign('lokasi_barang_id')
                ->references('id')
                ->on('lokasi_barang')
                ->restrictOnDelete();
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->year('tahun_perolehan')->nullable();
            $table->enum('kondisi', [
                'baik',
                'rusak_ringan',
                'rusak_berat'
            ])->default('baik');
            $table->enum('status', [
                'aktif',
                'dipinjam',
                'hilang',
                'dihapus'
            ])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
