<?php

namespace App\Services;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use Illuminate\Support\Collection;

class EkstrakurikulerService
{
    /**
     * Mengambil daftar siswa yang belum memiliki Ekstrakurikuler di Tahun Ajaran tertentu.
     */
    public function getSiswaBelumMasuk(int $tahunAjaranId): Collection
    {
        $kelasIds = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->pluck('id');

        return AnggotaKelas::with(['siswa:nis,nama_lengkap', 'kelas:id,nama_kelas'])
            ->whereIn('kelas_id', $kelasIds)
            // Catatan Architect: Jika 1 siswa BOLEH ikut >1 Ekskul, logika whereDoesntHave ini harus diubah.
            ->whereDoesntHave('anggotaEkstrakurikuler') 
            ->get()
            ->map(fn ($anggota) => (object) [
                'id'         => $anggota->id,
                'nis'        => $anggota->siswa->nis ?? '-',
                'siswa_nama' => $anggota->siswa->nama_lengkap ?? '-',
                'kelas'      => $anggota->kelas->nama_kelas ?? '-',
            ]);
    }
}