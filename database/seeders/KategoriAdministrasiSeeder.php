<?php

namespace Database\Seeders;

use App\Models\KategoriAdministrasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriAdministrasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriAdministrasi::insert([
            [
                'nama_kategori' => 'Alur Tujuan Pembelajaran',
                'semester' => false,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Program Tahunan',
                'semester' => false,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Program Semester',
                'semester' => false,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Refleksi Harian',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lesson Plan',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Media Pembelajaran',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Dokumen ASTS',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Dokumen ASAS',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Program Evaluasi',
                'semester' => true,
                'jenis' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Jurnal Harian',
                'semester' => true,
                'jenis' => 'kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Dokumentasi Kelas',
                'semester' => true,
                'jenis' => 'kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Jadwal Pelajaran',
                'semester' => true,
                'jenis' => 'kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Dekorasi Kelas',
                'semester' => true,
                'jenis' => 'kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
