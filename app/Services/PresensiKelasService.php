<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiKelasService
{
    public function getKelasContext($user, TahunAjaran $tahunAjaran, ?int $kelasId = null): ?Kelas
    {
        if ($user->hasRole('guru_sd')) {
            return Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                ->where(function ($query) use ($user) {
                    $query->where('guru_nipy', $user->email)
                          ->orWhere('pendamping_nipy', $user->email);
                })->first();
        }

        if ($user->hasAnyRole(['admin', 'dapur']) && $kelasId) {
            return Kelas::find($kelasId);
        }

        return null;
    }

    /**
     * Menyimpan presensi manual (dari web) secara optimal
     */
    public function simpanPresensiMassal(array $data): void
    {
        $tanggalBase = Carbon::parse($data['tanggal'])->toDateString();
        $jamMasukStandar = Carbon::parse($tanggalBase . ' 07:30:00');

        // Gunakan DB Transaction agar jika gagal di tengah, data tidak corrupt
        DB::transaction(function () use ($data, $tanggalBase, $jamMasukStandar) {
            foreach ($data['presensi'] as $anggota_kelas_id => $status) {
                if (empty($status)) continue;

                $waktuInput = $data['waktu'][$anggota_kelas_id] ?? '07:30';
                $waktuPresensi = Carbon::parse($tanggalBase . ' ' . $waktuInput);

                $isLate = false;
                $lateMinutes = 0;

                if ($status === 'Hadir') {
                    $isLate = $waktuPresensi->gt($jamMasukStandar);
                    $lateMinutes = $isLate ? $jamMasukStandar->diffInMinutes($waktuPresensi) : 0;
                }

                // Optimalisasi: updateOrCreate langsung menangani logika cek & insert
                Presensi::updateOrCreate(
                    [
                        'anggota_kelas_id' => $anggota_kelas_id,
                        // Pastikan format pencarian tanggal sesuai dengan tipe data date
                        'tanggal' => $waktuPresensi->format('Y-m-d H:i:s') 
                    ],
                    [
                        'status'          => $status,
                        'terlambat'       => $isLate,
                        'menit_terlambat' => $lateMinutes,
                    ]
                );
            }
        });
    }

    public function hitungStatistik(int $kelasId, Carbon $tanggalAwal, Carbon $tanggalAkhir): array
    {
        // 1. TAMBAHAN: Ambil data anggota kelas beserta relasi siswa
        $anggotaKelas = AnggotaKelas::with('siswa')->where('kelas_id', $kelasId)->get();
        $anggotaKelasIds = $anggotaKelas->pluck('id');

        // AMBIL SEMUA DATA SEKALIGUS KE RAM (1 Query)
        $presensiList = Presensi::whereIn('anggota_kelas_id', $anggotaKelasIds)
            ->whereBetween('tanggal', [$tanggalAwal->startOfDay(), $tanggalAkhir->endOfDay()])
            ->get();

        // Hitung koleksi menggunakan fungsi bawaan Laravel Collection
        $tanggalTercatat = $presensiList->pluck('tanggal')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->unique()->sort()->values();

        $hariEfektif = $tanggalTercatat->count();
        $totalHadir = $presensiList->where('status', 'Hadir')->count();
        $totalTepatWaktu = $presensiList->where('status', 'Hadir')->where('terlambat', false)->count();

        $persenHadir = 0;
        $persenTidakHadir = 0;
        $persenTepatWaktu = 0;
        $persenTerlambat = 0;

        if ($hariEfektif > 0 && $anggotaKelasIds->count() > 0) {
            $persenHadir = round(($totalHadir / ($hariEfektif * $anggotaKelasIds->count())) * 100, 1);
            $persenTidakHadir = 100 - $persenHadir;
            
            if ($totalHadir > 0) {
                $persenTepatWaktu = round(($totalTepatWaktu / $totalHadir) * 100, 1);
                $persenTerlambat = 100 - $persenTepatWaktu;
            }
        }

        return [
            'persentaseHadir'      => $persenHadir,
            'persentaseTidakHadir' => $persenTidakHadir,
            'persentaseTepatWaktu' => $persenTepatWaktu,
            'persentaseTerlambat'  => $persenTerlambat,
            'presensi'             => $presensiList,
            'tanggal_tercatat'     => $tanggalTercatat,
            'anggotaKelas'         => $anggotaKelas, // 2. TAMBAHAN: Pastikan ini direturn agar View tidak error
        ];
    }
}