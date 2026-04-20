<?php

namespace App\Services\Puskesmas;

use App\Models\Kelas;
use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use App\Models\AnggotaKelas;
use Illuminate\Support\Collection;

class PuskesmasKesehatanService
{
    public function getProgresKesehatan(TahunAjaran $tahunAjaran, BulanSpp $bulan): Collection
    {
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
            ->withCount('anggotaKelas')
            ->get();

        $terisiPerKelas = AnggotaKelas::whereIn('kelas_id', $kelasList->pluck('id'))
            ->whereHas('dataKesehatan', function ($query) use ($bulan) {
                $query->where('bulan_spp_id', $bulan->id);
            })
            ->selectRaw('kelas_id, count(*) as terisi_count')
            ->groupBy('kelas_id')
            ->pluck('terisi_count', 'kelas_id');

        return $kelasList->map(function ($kelas) use ($terisiPerKelas) {
            $totalSiswa = $kelas->anggota_kelas_count;
            $sudahIsi  = $terisiPerKelas->get($kelas->id, 0);
            $persen    = $totalSiswa > 0 ? round(($sudahIsi / $totalSiswa) * 100) : 0;

            return (object) [
                'kelas'  => $kelas,
                'total'  => $totalSiswa,
                'terisi' => $sudahIsi,
                'persen' => $persen,
            ];
        });
    }

    public function getAnggotaDenganKesehatan(Kelas $kelas, BulanSpp $bulan): Collection
    {
        return AnggotaKelas::with([
            'siswa', 
            'dataKesehatan' => function ($query) use ($bulan) {
                $query->where('bulan_spp_id', $bulan->id);
            }
        ])
        ->where('kelas_id', $kelas->id)
        ->get();
    }
}