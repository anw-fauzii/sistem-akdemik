<?php

namespace App\Services;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\AnggotaKelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class AnggotaEkstrakurikulerService
{
    /**
     * Memasukkan banyak siswa ke dalam Ekskul sekaligus (Bulk Insert & Update).
     */
    public function assignBulk(array $anggotaKelasIds, int $ekstrakurikulerId): void
    {
        DB::transaction(function () use ($anggotaKelasIds, $ekstrakurikulerId) {
            // 1. Dapatkan NIS dari ID Anggota Kelas (1 Query)
            $nisList = AnggotaKelas::whereIn('id', $anggotaKelasIds)->pluck('siswa_nis');

            // 2. Update massal tabel Siswa (1 Query - Mencegah Loop of Death)
            Siswa::whereIn('nis', $nisList)->update(['ekstrakurikuler_id' => $ekstrakurikulerId]);

            // 3. Mapping data array untuk Bulk Insert
            $now = now();
            $insertData = collect($anggotaKelasIds)->map(fn($id) => [
                'anggota_kelas_id'   => $id,
                'ekstrakurikuler_id' => $ekstrakurikulerId,
                'created_at'         => $now,
                'updated_at'         => $now,
            ])->toArray();

            // 4. Bulk Insert tabel Anggota Ekskul (1 Query)
            AnggotaEkstrakurikuler::insert($insertData);
        });
    }

    /**
     * Menghapus siswa dari Ekskul.
     */
    public function remove(AnggotaEkstrakurikuler $anggotaEkskul): void
    {
        DB::transaction(function () use ($anggotaEkskul) {
            // Load relasi mencegah N+1
            $anggotaEkskul->load('anggotaKelas.siswa');

            // Lepaskan ID ekskul dari tabel siswa
            if ($siswa = $anggotaEkskul->anggotaKelas?->siswa) {
                $siswa->update(['ekstrakurikuler_id' => null]);
            }

            // Hapus anggota dari kelompok ekskul
            $anggotaEkskul->delete();
        });
    }
}