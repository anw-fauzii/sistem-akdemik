<?php

namespace App\Services;

use App\Models\Ekstrakurikuler;
use App\Models\PresensiEkstrakurikuler;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiEkstrakurikulerService
{
    /**
     * Mengambil Ekstrakurikuler yang diampu oleh Guru pada Tahun Ajaran tertentu.
     */
    public function getEkstrakurikulerGuru(string $nipy, int $tahunAjaranId): ?Ekstrakurikuler
    {
        return Ekstrakurikuler::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('guru_nipy', $nipy)
            ->first();
    }

    /**
     * Menyimpan atau memperbarui data presensi massal dalam 1 Transaksi Database.
     */
    public function simpanPresensiMassal(array $presensiData, string $tanggal): void
    {
        DB::transaction(function () use ($presensiData, $tanggal) {
            foreach ($presensiData as $anggotaId => $status) {
                PresensiEkstrakurikuler::updateOrCreate(
                    [
                        'anggota_ekstrakurikuler_id' => $anggotaId,
                        'tanggal'                    => $tanggal,
                    ],
                    [
                        'status'                     => $status
                    ]
                );
            }
        });
    }
}