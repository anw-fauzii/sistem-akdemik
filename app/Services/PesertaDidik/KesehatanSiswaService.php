<?php

namespace App\Services\PesertaDidik;

use App\Models\AnggotaKelas;
use App\Models\Kesehatan;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class KesehatanSiswaService
{
    public function getRiwayatBelajar(string $nis): Collection
    {
        return AnggotaKelas::with('kelas.tahun_ajaran')
            ->where('siswa_nis', $nis)
            ->get();
    }

    public function getAnggotaKelasByTahun(string $nis, TahunAjaran $tahunAjaran): ?AnggotaKelas
    {
        return AnggotaKelas::where('siswa_nis', $nis)
            ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })
            ->first();
    }

    public function getDataKesehatan(int $anggotaKelasId): Collection
    {
        return Kesehatan::with('bulanSpp')
            ->where('anggota_kelas_id', $anggotaKelasId)
            ->orderBy('bulan_spp_id', 'asc')
            ->get();
    }
}