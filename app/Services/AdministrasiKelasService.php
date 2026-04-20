<?php

namespace App\Services;

use App\Models\AdministrasiKelas;
use App\Models\KategoriAdministrasi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Exception;

class AdministrasiKelasService
{
    public function getKelasAktif(string $nipy, int $tahunAjaranId): ?Kelas
    {
        return Kelas::where(function ($q) use ($nipy) {
            $q->where('guru_nipy', $nipy)
              ->orWhere('pendamping_nipy', $nipy);
        })->where('tahun_ajaran_id', $tahunAjaranId)->first();
    }

    public function uploadFiles(array $data, array $files, Kelas $kelas, TahunAjaran $tahunAjaran): void
    {
        $kategori = KategoriAdministrasi::findOrFail($data['kategori_administrasi_id']);
        $namaTahunAjaran = str_replace('/', '_', $tahunAjaran->nama_tahun_ajaran);
        
        $basePath = "{$namaTahunAjaran}/Kelas/{{$kelas->nama_kelas}}/{$kategori->nama_kategori}";
        if (!empty($data['semester'])) {
            $basePath .= "/Semester {$data['semester']}";
        }

        DB::transaction(function () use ($files, $basePath, $tahunAjaran, $kelas, $data) {
            foreach ($files as $file) {
                // Timestamp untuk mencegah bentrok nama file (Overwrite) di GDrive
                $safeFilename = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
                $fullPath = $basePath . '/' . $safeFilename;

                Gdrive::put($fullPath, $file);

                AdministrasiKelas::create([
                    'tahun_ajaran_id'          => $tahunAjaran->id,
                    'kelas_id'                 => $kelas->id,
                    'kategori_administrasi_id' => $data['kategori_administrasi_id'],
                    'keterangan'               => $file->getClientOriginalName(),
                    'link'                     => $fullPath,
                ]);
            }
        });
    }

    public function deleteFile(AdministrasiKelas $administrasi): void
    {
        DB::transaction(function () use ($administrasi) {
            try {
                Gdrive::delete($administrasi->link);
            } catch (Exception $e) {
                Log::warning("File GDrive Kelas tidak ditemukan: " . $administrasi->link);
            }
            $administrasi->delete();
        });
    }
}