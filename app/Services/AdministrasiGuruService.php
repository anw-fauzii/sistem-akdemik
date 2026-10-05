<?php

namespace App\Services;

use App\Models\AdministrasiGuru;
use App\Models\Guru;
use App\Models\KategoriAdministrasi;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Exception;

class AdministrasiGuruService
{
    public function uploadFiles(array $data, $file): void
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kategori = KategoriAdministrasi::findOrFail($data['kategori_administrasi_id']);
        $user = Auth::user();
        $namaTahunAjaran = str_replace('/', '_', $tahunAjaran->nama_tahun_ajaran);
        
        $basePath = "{$namaTahunAjaran}/Per Guru/{$user->email}_{$user->name}/{$kategori->nama_kategori}";
        if (!empty($data['semester'])) {
            $basePath .= "/Semester {$data['semester']}";
        }
        DB::transaction(function () use ($file, $basePath, $tahunAjaran, $user, $data) {
            $safeFilename = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $fullPath = $basePath . '/' . $safeFilename;
            Gdrive::put($fullPath, $file);
            AdministrasiGuru::create([
                'tahun_ajaran_id'          => $tahunAjaran->id,
                'guru_nipy'                => $user->email,
                'kategori_administrasi_id' => $data['kategori_administrasi_id'],
                'keterangan'               => $file->getClientOriginalName(),
                'link'                     => $fullPath,
            ]);
        });
    }

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

    public function getAdministrasiGroupedByGuru()
    {
        return Guru::with(['administrasiGuru.kategoriAdministrasi'])
            ->orderBy('nama_lengkap', 'asc')
            ->get();
    }

    public function verifyAdministrasi(AdministrasiGuru $administrasiGuru): bool
    {
        return $administrasiGuru->update([
            'status' => true 
        ]);
    }
}