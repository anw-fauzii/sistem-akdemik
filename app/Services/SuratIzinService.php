<?php

namespace App\Services;

use App\Models\SuratIzin;
use App\Models\AnggotaKelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SuratIzinService
{
    /**
     * Dapatkan anggota kelas siswa yang sedang aktif.
     */
    public function getActiveStudentMember(string $email): ?AnggotaKelas
    {
        return AnggotaKelas::whereSiswaNis($email)
            ->tahunAjaranAktif()
            ->first();
    }

    /**
     * Kueri untuk Role Siswa: Hanya melihat surat izinnnya sendiri.
     */
    public function getListForSiswa(string $email): Collection
    {
        $anggota = $this->getActiveStudentMember($email);

        if (!$anggota) {
            return collect();
        }

        // Menggunakan Eager Loading agar UI Blade tidak terkena N+1 jika memanggil data kelas/siswa
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('anggota_kelas_id', $anggota->id)
            ->latest()
            ->get(); 
    }

    /**
     * Kueri untuk Role Guru: Hanya melihat surat izin dari kelas yang diajarnya.
     */
    public function getListForGuru(string $email): Collection
    {
        // Eloquent Optimization: Menggunakan whereHas untuk memfilter relasi langsung di level database
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->whereHas('anggotaKelas.kelas', function ($query) use ($email) {
                $query->where('guru_nipy', $email)
                      ->orWhere('pendamping_nipy', $email);
            })
            ->latest()
            ->get();
    }

    /**
     * Kueri untuk Role Admin: Melihat seluruh data surat izin.
     */
    public function getAllList(): Collection
    {
        return SuratIzin::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->latest()
            ->get();
    }

    public function store(array $data, ?UploadedFile $file): SuratIzin
    {
        if ($file) {
            $data['file'] = $file->store('surat_izin', 'public');
        }

        return SuratIzin::create($data);
    }

    public function update(SuratIzin $surat, array $data, ?UploadedFile $file): bool
    {
        if ($file) {
            if ($surat->file) Storage::disk('public')->delete($surat->file);
            $data['file'] = $file->store('surat_izin', 'public');
        }

        return $surat->update($data);
    }

    public function delete(SuratIzin $surat): bool
    {
        if ($surat->file) Storage::disk('public')->delete($surat->file);
        return $surat->delete();
    }
}