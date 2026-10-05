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
        Schema::create('indeks_masa_tubuh', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->unsignedSmallInteger('umur_bulan');
            $table->decimal('minus_3_sd', 5, 2);
            $table->decimal('minus_2_sd', 5, 2);
            $table->decimal('minus_1_sd', 5, 2);
            $table->decimal('median', 5, 2);
            $table->decimal('plus_1_sd', 5, 2);
            $table->decimal('plus_2_sd', 5, 2);
            $table->decimal('plus_3_sd', 5, 2);

            $table->timestamps();
            $table->unique(
                ['jenis_kelamin', 'umur_bulan'],
                'imt_jenis_kelamin_umur_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indeks_masa_tubuh');
    }
};