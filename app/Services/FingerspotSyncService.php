<?php

namespace App\Services;

use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FingerspotSyncService
{
    /**
     * Mengambil data dari API Fingerspot dan menyimpannya ke database.
     */
    public function syncToday(): void
    {
        $cloudIds = [
            'C2630450C30A1D24', 'C262C44523180D2B', 'C262C4452319112B',
            'C262C44523201F31', 'C262C44523270B2F', 'C262C4452336242D',
            'C2630450C3391926'
        ];
        
        $apiToken = config('services.fingerspot.api_token');
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $allLogs = [];

        foreach ($cloudIds as $cloudId) {
            $response = Http::retry(3, 1000)->timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type'  => 'application/json',
            ])->post('https://developer.fingerspot.io/api/get_attlog', [
                'trans_id'   => uniqid(),
                'cloud_id'   => $cloudId,
                'start_date' => $today,
                'end_date'   => $today,
            ]);

            if (!$response->successful()) {
                Log::error("Fingerspot gagal ambil data untuk Cloud ID: {$cloudId}", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                continue;
            }

            foreach ($response->json('data', []) as $log) {
                if (!isset($log['pin'], $log['scan_date'])) continue;

                $nis = $log['pin'];
                $waktu = Carbon::parse($log['scan_date']);
                
                // Hanya ambil scan pertama (paling awal) di hari tersebut
                if (!isset($allLogs[$nis]) || $waktu->lt($allLogs[$nis])) {
                    $allLogs[$nis] = $waktu;
                }
            }
        }

        $this->processLogsToDatabase($allLogs, $today);
    }

    /**
     * Memproses data mentah ke tabel Presensi
     */
    private function processLogsToDatabase(array $logs, string $tanggal): void
    {
        DB::transaction(function () use ($logs, $tanggal) {
            foreach ($logs as $nis => $waktuMasuk) {
                // Normalisasi NIS kotor (Zero-width space/NBSP)
                $cleanNis = preg_replace('/[\s\x{00A0}\x{200B}]/u', '', trim($nis));

                $siswa = Siswa::whereRaw('REPLACE(REPLACE(REPLACE(nis, CHAR(194,160), ""), CHAR(226,128,139), ""), " ", "") = ?', [$cleanNis])
                    ->with('anggotaKelasAktif') // Eager load untuk cegah N+1
                    ->first();

                if (!$siswa || !$siswa->anggotaKelasAktif) {
                    Log::warning('Sinkronisasi Absen: Siswa/Anggota Kelas tidak ditemukan', ['nis' => $cleanNis]);
                    continue;
                }

                $jamMasuk = Carbon::parse($tanggal . ' 07:35');
                $menitTerlambat = max(0, $jamMasuk->diffInMinutes($waktuMasuk, false));

                Presensi::firstOrCreate(
                    [
                        'anggota_kelas_id' => $siswa->anggotaKelasAktif->id,
                        'tanggal'          => $tanggal, 
                    ],
                    [
                        'status'          => 'hadir',
                        'terlambat'       => $menitTerlambat > 0,
                        'menit_terlambat' => $menitTerlambat,
                    ]
                );
            }
        });
    }
}