<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class AkumulasiPoinKedisiplinanService
{
    /**
     * Mengambil daftar kelas untuk dropdown filter.
     */
    public function getDaftarKelas(): Collection
    {
        $tahunAjaran = TahunAjaran::latest()->first();

        return Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->orderBy('nama_kelas')->get();
    }

    /**
     * Mengambil data siswa berdasarkan kelas beserta akumulasi poinnya.
     */
    public function getAkumulasiByKelas(int $kelasId): Collection
    {
        $anggotaKelas = AnggotaKelas::with(['siswa', 'riwayatKedisiplinan.kedisiplinanPoin'])
                                    ->where('kelas_id', $kelasId)
                                    ->get();

        $anggotaKelas->map(function ($anggota) {
            $totalPoin = 0;
            $poinPrestasi = 0;
            $poinPelanggaran = 0;

            foreach ($anggota->riwayatKedisiplinan as $riwayat) {
                $aturan = $riwayat->kedisiplinanPoin;
                
                if ($aturan) {
                    if (strtolower($aturan->kategori) === 'prestasi') {
                        $totalPoin += $aturan->poin;
                        $poinPrestasi += $aturan->poin; // Simpan detail jika ingin ditampilkan
                    } elseif (strtolower($aturan->kategori) === 'pelanggaran') {
                        $totalPoin -= $aturan->poin;
                        $poinPelanggaran += $aturan->poin; // Simpan detail jika ingin ditampilkan
                    }
                }
            }

            // Memasukkan hasil hitungan sebagai properti dinamis ke dalam object
            $anggota->total_poin = $totalPoin;
            $anggota->poin_prestasi = $poinPrestasi;
            $anggota->poin_pelanggaran = $poinPelanggaran;

            return $anggota;
        });

        // Urutkan berdasarkan total poin (dari yang paling minus hingga plus)
        // Bisa diubah menjadi sortByDesc jika ingin yang poinnya paling tinggi di atas
        return $anggotaKelas->sortBy('total_poin')->values(); 
    }
}