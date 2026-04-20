<?php

namespace App\Services\PesertaDidik;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PresensiSiswaService
{
    public function getAnggotaKelasAktif(string $nis, TahunAjaran $tahunAjaran): ?AnggotaKelas
    {
        return AnggotaKelas::where('siswa_nis', $nis)
            ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })
            ->first();
    }

    public function getDaftarBulan(int $tahunAjaranId): Collection
    {
        return BulanSpp::where('tahun_ajaran_id', $tahunAjaranId)->get();
    }

    public function getPresensiByBulan(int $anggotaKelasId, string $tanggalAcuan): Collection
    {
        $date = Carbon::parse($tanggalAcuan);
        return Presensi::where('anggota_kelas_id', $anggotaKelasId)
            ->whereMonth('tanggal', $date->month)
            ->whereYear('tanggal', $date->year)
            ->orderBy('tanggal', 'asc')
            ->get();
    }
}