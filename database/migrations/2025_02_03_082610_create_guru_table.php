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
        Schema::create('guru', function (Blueprint $table) {
            $table->string('nipy', 100)->primary();
            $table->foreign('nipy')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_lengkap', 100);
            $table->string('gelar', 10);
            $table->string('jabatan');
            $table->string('telepon');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->string('nuptk', 16)->unique()->nullable();
            $table->string('alamat');
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
