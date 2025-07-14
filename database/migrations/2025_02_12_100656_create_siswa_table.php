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
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('nis', 100)->primary();
            $table->unsignedBigInteger('kelas_id')->unsigned()->nullable();
            $table->string('guru_nipy')->nullable();
            $table->unsignedBigInteger('ekstrakurikuler_id')->unsigned()->nullable();
            $table->enum('jenis_pendaftaran', ['1', '2']);
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nisn', 20)->unique()->nullable();
            $table->bigInteger('nik')->nullable();
            $table->bigInteger('no_kk')->nullable();
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('akta_lahir', 100)->unique()->nullable();
            $table->enum('agama', ['1', '2', '3', '4', '5', '6', '7']);
            $table->enum('kewarganegaraan', ['WNI', 'WNA']);
            $table->string('nama_negara', 50)->nullable();
            $table->unsignedBigInteger('berkebutuhan_khusus_id')->unsigned();
            $table->string('alamat');
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('desa', 50)->nullable();
            $table->string('kecamatan', 50)->nullable();
            $table->string('kabupaten', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kode_pos', 50)->nullable();
            $table->string('lintang', 50)->nullable();
            $table->string('bujur', 50)->nullable();
            $table->enum('tempat_tinggal', ['1', '2', '3', '4', '5']);
            $table->unsignedBigInteger('transportasi_id')->unsigned();
            $table->string('anak_ke', 2);
            $table->string('jumlah_saudara', 2);
            
            $table->bigInteger('nik_ayah')->unique()->nullable();
            $table->string('nama_ayah', 150)->nullable();
            $table->integer('lahir_ayah')->nullable();
            $table->unsignedBigInteger('jenjang_pendidikan_ayah_id')->nullable();
            $table->unsignedBigInteger('pekerjaan_ayah_id')->nullable();
            $table->unsignedBigInteger('penghasilan_ayah_id')->nullable();
            $table->unsignedBigInteger('berkebutuhan_khusus_ayah_id')->nullable();

            $table->bigInteger('nik_ibu')->unique()->nullable();
            $table->string('nama_ibu', 150)->nullable();
            $table->integer('lahir_ibu')->nullable();
            $table->unsignedBigInteger('jenjang_pendidikan_ibu_id')->nullable();
            $table->unsignedBigInteger('pekerjaan_ibu_id')->nullable();
            $table->unsignedBigInteger('penghasilan_ibu_id')->nullable();
            $table->unsignedBigInteger('berkebutuhan_khusus_ibu_id')->nullable();

            $table->bigInteger('nik_wali')->unique()->nullable();
            $table->string('nama_wali', 150)->nullable();
            $table->integer('lahir_wali')->nullable();
            $table->unsignedBigInteger('jenjang_pendidikan_wali_id')->nullable();
            $table->unsignedBigInteger('pekerjaan_wali_id')->nullable();
            $table->unsignedBigInteger('penghasilan_wali_id')->nullable();
            $table->unsignedBigInteger('berkebutuhan_khusus_wali_id')->nullable();

            $table->string('nomor_hp', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('email', 30)->nullable();

            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->integer('jarak')->nullable();
            $table->integer('waktu_tempuh')->nullable();
            $table->integer('lingkar_kepala')->nullable();

            $table->unsignedBigInteger('tarif_spp_id')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1');
            $table->timestamps();

            $table->foreign('nis')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('guru_nipy')->references('nipy')->on('guru')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ekstrakurikuler_id')->references('id')->on('ekstrakurikuler')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('berkebutuhan_khusus_id')->references('id')->on('berkebutuhan_khusus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transportasi_id')->references('id')->on('transportasi')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('jenjang_pendidikan_ayah_id')->references('id')->on('jenjang_pendidikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pekerjaan_ayah_id')->references('id')->on('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('penghasilan_ayah_id')->references('id')->on('penghasilan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('berkebutuhan_khusus_ayah_id')->references('id')->on('berkebutuhan_khusus')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('jenjang_pendidikan_ibu_id')->references('id')->on('jenjang_pendidikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pekerjaan_ibu_id')->references('id')->on('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('penghasilan_ibu_id')->references('id')->on('penghasilan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('berkebutuhan_khusus_ibu_id')->references('id')->on('berkebutuhan_khusus')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('jenjang_pendidikan_wali_id')->references('id')->on('jenjang_pendidikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pekerjaan_wali_id')->references('id')->on('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('penghasilan_wali_id')->references('id')->on('penghasilan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('berkebutuhan_khusus_wali_id')->references('id')->on('berkebutuhan_khusus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tarif_spp_id')->references('id')->on('tarif_spp')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
