<?php

namespace App\Services;

use App\Models\AdministrasiGuru;
use App\Models\KategoriAdministrasi;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Exception;

class AdministrasiGuruService
{
    /**
     * Memproses upload file ke Google Drive dan mencatatnya di Database.
     */
    public function uploadFiles(array $data, array $files): void
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kategori = KategoriAdministrasi::findOrFail($data['kategori_administrasi_id']);
        $user = Auth::user();

        // Sanitasi nama folder untuk mencegah karakter ilegal
        $namaTahunAjaran = str_replace('/', '_', $tahunAjaran->nama_tahun_ajaran);
        
        $basePath = "{$namaTahunAjaran}/Per Guru/{$user->email}_{$user->name}/{$kategori->nama_kategori}";
        if (!empty($data['semester'])) {
            $basePath .= "/Semester {$data['semester']}";
        }

        // Jalankan Database Transaction
        DB::transaction(function () use ($files, $basePath, $tahunAjaran, $user, $data) {
            foreach ($files as $file) {
                // Rekomendasi: Tambahkan timestamp agar nama file unik & tidak tertimpa
                $safeFilename = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
                $fullPath = $basePath . '/' . $safeFilename;

                // Upload ke GDrive
                Gdrive::put($fullPath, $file);

                // Catat ke DB
                AdministrasiGuru::create([
                    'tahun_ajaran_id'          => $tahunAjaran->id,
                    'guru_nipy'                => $user->email,
                    'kategori_administrasi_id' => $data['kategori_administrasi_id'],
                    'keterangan'               => $file->getClientOriginalName(),
                    'link'                     => $fullPath,
                ]);
            }
        });
    }

    /**
     * Menghapus file dari GDrive dan Database.
     */
    public function deleteFile(AdministrasiGuru $administrasi): void
    {
        DB::transaction(function () use ($administrasi) {
            try {
                Gdrive::delete($administrasi->link);
            } catch (Exception $e) {
                // Log error jika file di GDrive sudah hilang/terhapus manual, tapi tetap hapus data DB-nya
                \Illuminate\Support\Facades\Log::warning("File GDrive tidak ditemukan: " . $administrasi->link);
            }
            $administrasi->delete();
        });
    }
}