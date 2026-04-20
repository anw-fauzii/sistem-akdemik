<?php

namespace App\Services;

use App\Models\PrestasiSiswa;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrestasiService
{
    public function getPrestasiForUser($user)
    {
        if ($user->hasRole('admin')) {
            return PrestasiSiswa::with('anggotaKelas.siswa', 'anggotaKelas.kelas')->latest()->get();
        }

        $tahun = TahunAjaran::latest()->first();
        // Menggunakan scope tahunAjaranAktif dari model AnggotaKelas
        $anggota = AnggotaKelas::whereSiswaNis($user->email)->tahunAjaranAktif()->first();

        return $anggota ? $anggota->prestasi()->latest()->get() : collect();
    }

    public function getAnggotaKelasForSelection()
    {
        return AnggotaKelas::with(['siswa', 'kelas'])
            ->tahunAjaranAktif()
            ->get();
    }

    // app/Services/PrestasiService.php

    public function store(array $data, $file = null): PrestasiSiswa
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['file_sertifikat'] = $file->store('prestasiSiswa', 'public');
            }

            // PERBAIKAN: Pisahkan anggota_kelas_id agar tidak ikut di-insert ke tabel prestasi_siswa
            $siswaIds = $data['anggota_kelas_id'];
            
            // Simpan hanya data yang memang ada kolomnya di tabel prestasi_siswa
            $prestasi = PrestasiSiswa::create(collect($data)->except('anggota_kelas_id')->toArray());

            // Simpan relasi ke tabel pivot
            $prestasi->anggotaKelas()->sync($siswaIds);
            
            return $prestasi;
        });
    }

    public function update(PrestasiSiswa $prestasi, array $data, $file = null): bool
    {
        return DB::transaction(function () use ($prestasi, $data, $file) {
            if ($file) {
                if ($prestasi->file_sertifikat) {
                    Storage::disk('public')->delete($prestasi->file_sertifikat);
                }
                $data['file_sertifikat'] = $file->store('prestasiSiswa', 'public');
            }

            // PERBAIKAN: Pisahkan anggota_kelas_id dari data update
            if (isset($data['anggota_kelas_id'])) {
                $prestasi->anggotaKelas()->sync($data['anggota_kelas_id']);
            }

            // Update tabel utama tanpa menyertakan field anggota_kelas_id
            return $prestasi->update(collect($data)->except('anggota_kelas_id')->toArray());
        });
    }

    /**
     * Method Delete: Menangani penghapusan file sertifikat dan data database.
     */
    public function delete(PrestasiSiswa $prestasi): bool
    {
        return DB::transaction(function () use ($prestasi) {
            // 1. Hapus file fisik dari storage
            if ($prestasi->file_sertifikat) {
                Storage::disk('public')->delete($prestasi->file_sertifikat);
            }

            // 2. Hapus relasi pivot terlebih dahulu (opsional jika cascade, tapi best practice)
            $prestasi->anggotaKelas()->detach();

            // 3. Hapus data utama
            return $prestasi->delete();
        });
    }
}