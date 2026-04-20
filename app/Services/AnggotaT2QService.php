<?php

namespace App\Services;

use App\Models\AnggotaT2Q;
use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnggotaT2QService
{
    /**
     * Memasukkan banyak siswa ke dalam T2Q sekaligus (Bulk Insert & Update) dalam 1 Transaksi.
     */
    public function assignBulk(array $anggotaKelasIds, string $guruNipy, string $tingkat): void
    {
        DB::transaction(function () use ($anggotaKelasIds, $guruNipy, $tingkat) {
            // 1. Ambil NIS list dalam 1 query
            $nisList = AnggotaKelas::whereIn('id', $anggotaKelasIds)->pluck('siswa_nis');

            // 2. Update massal tabel Siswa (HANYA 1 QUERY!)
            Siswa::whereIn('nis', $nisList)->update(['guru_nipy' => $guruNipy]);

            // 3. Mapping data untuk insert
            $now = now();
            $insertData = collect($anggotaKelasIds)->map(fn($id) => [
                'anggota_kelas_id' => $id,
                'guru_nipy'        => $guruNipy,
                'tingkat'          => $tingkat,
                'created_at'       => $now,
                'updated_at'       => $now,
            ])->toArray();

            // 4. Bulk Insert tabel Anggota T2Q (HANYA 1 QUERY!)
            AnggotaT2Q::insert($insertData);
        });
    }

    /**
     * Mengambil daftar siswa yang belum masuk T2Q pada tahun ajaran tertentu.
     */
    public function getSiswaBelumMasuk(int $tahunAjaranId): Collection
    {
        $kelasIds = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->pluck('id');

        return AnggotaKelas::with(['siswa:nis,nama_lengkap', 'kelas:id,nama_kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->whereDoesntHave('anggotaT2q')
            ->get()
            ->map(fn ($anggota) => (object) [
                'id'         => $anggota->id,
                'nis'        => $anggota->siswa->nis ?? '-',
                'siswa_nama' => $anggota->siswa->nama_lengkap ?? '-',
                'kelas'      => $anggota->kelas->nama_kelas ?? '-',
            ]);
    }

    public function remove(AnggotaT2Q $anggotaT2Q): void
    {
        DB::transaction(function () use ($anggotaT2Q) {
            // Eager Load untuk menghindari N+1 saat memanggil relasi
            $anggotaT2Q->load('anggotaKelas.siswa');

            // Update data Siswa
            if ($siswa = $anggotaT2Q->anggotaKelas?->siswa) {
                $siswa->update(['guru_nipy' => null]);
            }

            // Hapus data T2Q
            $anggotaT2Q->delete();
        });
    }
}