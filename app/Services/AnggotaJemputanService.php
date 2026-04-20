<?php

namespace App\Services;

use App\Models\AnggotaJemputan;
use App\Models\AnggotaKelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class AnggotaJemputanService
{
    /**
     * Memasukkan banyak siswa ke dalam jemputan sekaligus (Bulk Insert & Update).
     */
    public function assignBulk(array $anggotaKelasIds, int $jemputanId, ?string $keterangan): void
    {
        DB::transaction(function () use ($anggotaKelasIds, $jemputanId, $keterangan) {
            // 1. Dapatkan semua NIS siswa dari ID Anggota Kelas (Hanya 1 Query)
            $nisList = AnggotaKelas::whereIn('id', $anggotaKelasIds)->pluck('siswa_nis');

            // 2. Update massal tabel Siswa (Hanya 1 Query)
            Siswa::whereIn('nis', $nisList)->update(['jemputan_id' => $jemputanId]);

            // 3. Siapkan data array untuk Bulk Insert
            $now = now();
            $insertData = collect($anggotaKelasIds)->map(fn($id) => [
                'anggota_kelas_id' => $id,
                'jemputan_id'      => $jemputanId,
                'keterangan'       => $keterangan,
                'created_at'       => $now,
                'updated_at'       => $now,
            ])->toArray();

            // 4. Bulk Insert tabel Anggota Jemputan (Hanya 1 Query)
            AnggotaJemputan::insert($insertData);
        });
    }

    /**
     * Menghapus anggota dari jemputan.
     */
    public function remove(AnggotaJemputan $anggotaJemputan): void
    {
        DB::transaction(function () use ($anggotaJemputan) {
            // Eager Load untuk menghindari N+1 saat memanggil relasi
            $anggotaJemputan->load('anggotaKelas.siswa');

            // Update data Siswa
            if ($siswa = $anggotaJemputan->anggotaKelas?->siswa) {
                $siswa->update(['jemputan_id' => null]);
            }

            // Hapus data jemputan
            $anggotaJemputan->delete();
        });
    }
}