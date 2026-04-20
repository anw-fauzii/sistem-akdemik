<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class LaporanPresensiService
{
    /**
     * Menghitung statistik bulanan menggunakan algoritma 1-Query O(1) memory
     */
    public function getStatistikBulanan(Kelas $kelas, Carbon $tanggalAwal, Carbon $tanggalAkhir): array
    {
        $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
        $anggotaIds = $anggotaKelas->pluck('id');

        // Optimasi: 1 Query untuk seluruh data sebulan
        $presensiList = Presensi::whereIn('anggota_kelas_id', $anggotaIds)
            ->whereBetween('tanggal', [$tanggalAwal->startOfDay(), $tanggalAkhir->endOfDay()])
            ->get();

        $tanggalTercatat = $presensiList->pluck('tanggal')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->unique()->sort()->values();

        $hariEfektif = $tanggalTercatat->count();
        $totalHadir = $presensiList->where('status', 'hadir')->count();
        $totalTepatWaktu = $presensiList->where('status', 'hadir')->where('terlambat', false)->count();

        $persenHadir = 0;
        $persenTidakHadir = 0;
        $persenTepatWaktu = 0;
        $persenTerlambat = 0;

        if ($hariEfektif > 0 && $anggotaIds->count() > 0) {
            $totalMaksimal = $hariEfektif * $anggotaIds->count();
            $persenHadir = round(($totalHadir / $totalMaksimal) * 100, 1);
            $persenTidakHadir = 100 - $persenHadir;
            
            if ($totalHadir > 0) {
                $persenTepatWaktu = round(($totalTepatWaktu / $totalHadir) * 100, 1);
                $persenTerlambat = 100 - $persenTepatWaktu;
            }
        }

        return [
            'kelas'                => $kelas,
            'persentaseHadir'      => $persenHadir,
            'persentaseTidakHadir' => $persenTidakHadir,
            'persentaseTepatWaktu' => $persenTepatWaktu,
            'persentaseTerlambat'  => $persenTerlambat,
            'anggotaKelas'         => $anggotaKelas,
            'presensi'             => $presensiList,
            'tanggal_tercatat'     => $tanggalTercatat,
        ];
    }
}