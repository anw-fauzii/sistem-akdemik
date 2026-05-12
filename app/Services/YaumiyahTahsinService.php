<?php

namespace App\Services;

use App\Models\AnggotaT2Q;
use App\Models\YaumiyahTahsin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class YaumiyahTahsinService
{
    /**
     * Mengambil grup tingkat yang diajar oleh guru bersangkutan.
     */
    public function getGrupTingkatSiswa(string $guruNipy): Collection
    {
        return AnggotaT2Q::where('guru_nipy', $guruNipy)
            ->whereHas('anggotaKelas', function ($query) {
                $query->tahunAjaranAktif();
            })
            ->select('tingkat', DB::raw('count(*) as total_siswa'))
            ->groupBy('tingkat')
            ->get();
    }

    /**
     * Mengambil daftar siswa berdasarkan guru dan tingkatnya.
     */
    public function getSiswaByTingkat(string $guruNipy, string $tingkat): Collection
    {
        return AnggotaT2Q::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('guru_nipy', $guruNipy)
            ->whereHas('anggotaKelas', function ($query) {
                $query->tahunAjaranAktif();
            })
            ->where('tingkat', $tingkat)
            ->get()
            ->sortBy('anggotaKelas.kelas_id');
    }

    /**
     * Mengambil data yaumiyah yang sudah ada di tanggal tertentu (untuk view edit massal).
     */
    public function getExistingYaumiyah(string $guruNipy, string $tingkat, string $tanggal): \Illuminate\Support\Collection
    {
        $siswaIds = $this->getSiswaByTingkat($guruNipy, $tingkat)->pluck('id');
        
        return YaumiyahTahsin::with('daftarJilid')
            ->whereIn('anggota_t2q_id', $siswaIds)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('anggota_t2q_id');
    }

    /**
     * Menyimpan data massal menggunakan Database Transaction.
     */
    public function storeMassal(array $records): void
    {
        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                // Logika Emas: Skip jika jilid kosong (Siswa absen)
                if (empty($record['daftar_jilid_id'])) {
                    continue;
                }

                $surahId = !empty($record['surah_alquran_id']) ? $record['surah_alquran_id'] : null;
                $angkaId = !empty($record['angka_arab_id']) ? $record['angka_arab_id'] : null;
                $nilai   = $record['nilai'] !== null && $record['nilai'] !== '' ? (int)$record['nilai'] : null;

                YaumiyahTahsin::updateOrCreate(
                    [
                        'anggota_t2q_id' => $record['anggota_t2q_id'],
                        'tanggal'        => $record['tanggal'],
                    ],
                    [
                        'daftar_jilid_id'  => $record['daftar_jilid_id'],
                        'surah_alquran_id' => $surahId,
                        'angka_arab_id'    => $angkaId,
                        'nilai'            => $nilai,
                    ]
                );
            }
        });
    }

    public function getWeeklyMatrix(string $guruNipy, string $tingkat, string $tanggalPilih): array
    {
        return $this->generateMatrixData($guruNipy, $tingkat, $tanggalPilih, 1);
    }

    public function getTwoWeeklyMatrix(string $guruNipy, string $tingkat, string $tanggalPilih): array
    {
        return $this->generateMatrixData($guruNipy, $tingkat, $tanggalPilih, 2);
    }

    private function generateMatrixData(string $guruNipy, string $tingkat, string $tanggalPilih, int $jumlahMinggu): array
    {
        $carbonDate = Carbon::parse($tanggalPilih);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addWeeks($jumlahMinggu - 1)->addDays(3);

        $hariPekanIni = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            if (in_array($date->dayOfWeek, [\Carbon\Carbon::MONDAY, \Carbon\Carbon::TUESDAY, \Carbon\Carbon::WEDNESDAY, \Carbon\Carbon::THURSDAY])) {
                $hariPekanIni[] = $date->format('Y-m-d');
            }
        }

        $siswa = $this->getSiswaByTingkat($guruNipy, $tingkat);
        $siswaIds = $siswa->pluck('id');

        $yaumiyahRaw = YaumiyahTahsin::with(['daftarJilid', 'surahAlquran', 'angkaArab'])
            ->whereIn('anggota_t2q_id', $siswaIds)
            ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        $yaumiyah = [];
        foreach ($yaumiyahRaw as $record) {
            $tanggalString = $record->tanggal->format('Y-m-d');
            $yaumiyah[$record->anggota_t2q_id][$tanggalString] = $record;
        }

        return compact('tingkat', 'tanggalPilih', 'startOfWeek', 'endOfWeek', 'hariPekanIni', 'siswa', 'yaumiyah');
    }
}