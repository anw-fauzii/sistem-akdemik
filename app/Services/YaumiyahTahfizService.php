<?php

namespace App\Services;

use App\Models\AnggotaT2Q;
use App\Models\YaumiyahTahfiz;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class YaumiyahTahfizService
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
        
        return YaumiyahTahfiz::whereIn('anggota_t2q_id', $siswaIds)
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
                
                // LOGIKA EMAS TAHFIZ: 
                // Jika Surah tidak dipilih DAN nilai kosong, berarti siswa tidak setor hari itu. Abaikan!
                if (empty($record['surah_alquran_id']) && ($record['nilai'] === null || $record['nilai'] === '')) {
                    continue;
                }

                $surahId = !empty($record['surah_alquran_id']) ? $record['surah_alquran_id'] : null;
                $angkaId = !empty($record['angka_arab_id']) ? $record['angka_arab_id'] : null;
                $nilai   = $record['nilai'] !== null && $record['nilai'] !== '' ? (int)$record['nilai'] : null;

                YaumiyahTahfiz::updateOrCreate(
                    [
                        'anggota_t2q_id' => $record['anggota_t2q_id'],
                        'tanggal'        => $record['tanggal'],
                    ],
                    [
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
        $carbonDate = \Carbon\Carbon::parse($tanggalPilih);
        
        // 1. Tentukan Awal Pekan (Senin)
        $startOfWeek = $carbonDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        
        // 2. Tentukan Akhir Range Dinamis berdasarkan $jumlahMinggu
        // Jika 1 minggu = addWeeks(0), Jika 2 minggu = addWeeks(1)
        $endOfWeek = $startOfWeek->copy()->addWeeks($jumlahMinggu - 1)->addDays(3); // +3 hari = Kamis

        $hariPekanIni = [];
        
        // 3. Looping dari awal sampai akhir, filter HANYA Senin - Kamis
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            if (in_array($date->dayOfWeek, [\Carbon\Carbon::MONDAY, \Carbon\Carbon::TUESDAY, \Carbon\Carbon::WEDNESDAY, \Carbon\Carbon::THURSDAY])) {
                $hariPekanIni[] = $date->format('Y-m-d');
            }
        }

        // 4. Ambil Data Siswa
        $siswa = $this->getSiswaByTingkat($guruNipy, $tingkat);
        $siswaIds = $siswa->pluck('id');

        // 5. Eager Loading Data Yaumiyah
        $yaumiyahRaw = \App\Models\YaumiyahTahfiz::with(['surahAlquran', 'angkaArab'])
            ->whereIn('anggota_t2q_id', $siswaIds)
            ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        // 6. Mapping Data menjadi Array Multi-dimensi O(1) Lookup
        $yaumiyah = [];
        foreach ($yaumiyahRaw as $record) {
            $yaumiyah[$record->anggota_t2q_id][$record->tanggal->format('Y-m-d')] = $record;
        }

        $tahunAjaran = \App\Models\TahunAjaran::latest()->first();

        return compact('tingkat', 'tanggalPilih', 'startOfWeek', 'endOfWeek', 'hariPekanIni', 'siswa', 'yaumiyah', 'tahunAjaran');
    }
}