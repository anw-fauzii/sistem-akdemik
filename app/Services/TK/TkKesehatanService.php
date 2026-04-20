<?php

namespace App\Services\TK;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\AnggotaKelas;
use App\Models\Kesehatan;
use Illuminate\Database\Eloquent\Collection;

class TkKesehatanService
{
    public function getKelasGuruAktif(string $guruNipy, int $tahunAjaranId): ?Kelas
    {
        return Kelas::where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('guru_nipy', $guruNipy)
                    ->first();
    }

    public function getAnggotaDenganKesehatan(int $kelasId, int $bulanSppId): Collection
    {
        return AnggotaKelas::with([
            'siswa',
            'dataKesehatan' => function ($query) use ($bulanSppId) {
                $query->where('bulan_spp_id', $bulanSppId);
            }
        ])->where('kelas_id', $kelasId)->get();
    }

    public function simpanDataMassal(array $data, int $bulanId): void
    {
        $anggotaIds = $data['anggota_kelas_id'] ?? [];

        foreach ($anggotaIds as $anggotaId) {
            $record = [
                'tb'      => $data['tb'][$anggotaId] ?? null,
                'bb'      => $data['bb'][$anggotaId] ?? null,
                'lila'    => $data['lila'][$anggotaId] ?? null,
                'lika'    => $data['lika'][$anggotaId] ?? null,
                'lp'      => $data['lp'][$anggotaId] ?? null,
                'mata'    => $data['mata'][$anggotaId] ?? null,
                'telinga' => $data['telinga'][$anggotaId] ?? null,
                'gigi'    => $data['gigi'][$anggotaId] ?? null,
                'hasil'   => $data['hasil'][$anggotaId] ?? null,
                'tensi'   => $data['tensi'][$anggotaId] ?? null,
            ];

            // Hanya simpan jika minimal ada 1 field yang diisi (tidak kosong semua)
            if (collect($record)->filter()->isNotEmpty()) {
                Kesehatan::updateOrCreate(
                    [
                        'anggota_kelas_id' => $anggotaId,
                        'bulan_spp_id'     => $bulanId,
                    ],
                    $record
                );
            }
        }
    }
}