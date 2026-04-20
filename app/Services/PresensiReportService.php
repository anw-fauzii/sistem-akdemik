<?php

namespace App\Services;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PresensiReportService
{
    /**
     * Generate statistik untuk seluruh kelas SD di tahun ajaran aktif.
     */
    public function generateStatistikSemuaKelas(BulanSpp $bulan): array
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // Eager load anggota_kelas dan siswa untuk mencegah N+1 Query di Blade PDF/Excel
        $kelasList = Kelas::with(['anggotaKelas.siswa'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('jenjang', 'SD')
            ->get();

        if ($kelasList->isEmpty()) {
            throw new \Exception('Tidak ada data kelas SD pada tahun ajaran ini.');
        }

        $tanggalAwal = Carbon::parse($bulan->bulan_angka)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        $statistikPerKelas = [];

        foreach ($kelasList as $kelas) {
            $statistikPerKelas[] = [
                'kelas' => $kelas,
                ...$this->hitungStatistik($kelas, $tanggalAwal, $tanggalAkhir)
            ];
        }

        return $statistikPerKelas;
    }

    /**
     * Kalkulasi statistik murni menggunakan RAM (1 Kueri Database per Kelas).
     */
    public function hitungStatistik(Kelas $kelas, Carbon $awal, Carbon $akhir): array
    {
        $anggotaIds = $kelas->anggotaKelas->pluck('id');

        // KUERI MASTER: Tarik semua presensi kelas ini selama 1 bulan dalam 1 hitungan
        $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaIds)
            ->whereBetween('tanggal', [$awal->format('Y-m-d'), $akhir->format('Y-m-d')])
            ->get();

        // 1. Dapatkan daftar tanggal unik
        $tanggal_tercatat = $presensi->pluck('tanggal')
            ->map(fn($item) => Carbon::parse($item)->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values();

        // 2. Hitung jumlah Hari Efektif (tanggal unik)
        $hariEfektif = $tanggal_tercatat->count();
        $jumlahSiswa = $anggotaIds->count();
        
        // PERBAIKAN FATAL MATEMATIKA: 
        // Total seharusnya = 30 siswa x 20 hari efektif = 600 kehadiran
        $totalKehadiranSeharusnya = $hariEfektif * $jumlahSiswa;

        // 3. Kalkulasi via RAM (Cepat & Hemat CPU)
        $totalHadir = $presensi->where('status', 'hadir')->count();
        $totalTepatWaktu = $presensi->where('terlambat', false)->count();

        // 4. Hitung Persentase Akurat
        $persentaseHadir = 0;
        $persentaseTepatWaktu = 0;

        if ($totalKehadiranSeharusnya > 0) {
            $persentaseHadir = round(($totalHadir / $totalKehadiranSeharusnya) * 100, 1);
            $persentaseTepatWaktu = round(($totalTepatWaktu / $totalKehadiranSeharusnya) * 100, 1);
        }

        return [
            'persentaseHadir'      => $persentaseHadir,
            'persentaseTidakHadir' => round(100 - $persentaseHadir, 1),
            'persentaseTepatWaktu' => $persentaseTepatWaktu,
            'persentaseTerlambat'  => round(100 - $persentaseTepatWaktu, 1),
            'anggotaKelas'         => $kelas->anggotaKelas, // Sudah di-eager load
            'presensi'             => $presensi,
            'tanggal_tercatat'     => $tanggal_tercatat,
        ];
    }
}