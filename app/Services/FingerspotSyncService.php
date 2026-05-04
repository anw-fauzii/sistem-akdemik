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
                $tanggalKey = $waktu->toDateString();
                
                // Hanya simpan scan pertama (paling awal) per siswa per hari
                if (!isset($allLogs[$nis][$tanggalKey]) || $waktu->lt($allLogs[$nis][$tanggalKey])) {
                    $allLogs[$nis][$tanggalKey] = $waktu;
                }
            }
        }

        // Panggil service database. Parameter $today tidak perlu dikirim lagi
        $this->processLogsToDatabase($allLogs);
    }

    /**
     * Memproses data mentah ke tabel Presensi
     */
    private function processLogsToDatabase(array $logs): void
    {
        DB::transaction(function () use ($logs) {
            // Looping Level 1: NIS Siswa
            foreach ($logs as $nis => $dailyLogs) {
                
                $cleanNis = preg_replace('/[\s\x{00A0}\x{200B}]/u', '', trim($nis));

                $siswa = Siswa::whereRaw('REPLACE(REPLACE(REPLACE(nis, CHAR(194,160), ""), CHAR(226,128,139), ""), " ", "") = ?', [$cleanNis])
                    ->with('anggotaKelasAktif') 
                    ->first();

                if (!$siswa || !$siswa->anggotaKelasAktif) {
                    Log::warning('Sinkronisasi Absen: Siswa/Anggota Kelas tidak ditemukan', ['nis' => $cleanNis]);
                    continue; 
                }

                // Looping Level 2: Tanggal Scan (Karena satu siswa bisa punya data di beberapa tanggal jika API delay)
                foreach ($dailyLogs as $tanggalKey => $waktuMasuk) {
                    
                    // 1. Dinamis: Jam patokan berdasarkan tanggal scan, BUKAN hari ini
                    $jamMasuk = Carbon::parse($tanggalKey . ' 07:35:00');
                    $menitTerlambat = max(0, $jamMasuk->diffInMinutes($waktuMasuk, false));

                    // 2. CEK DUPLIKASI BERDASARKAN TANGGAL SAJA (Tanpa Jam)
                    $sudahAbsen = Presensi::where('anggota_kelas_id', $siswa->anggotaKelasAktif->id)
                        ->whereDate('tanggal', $tanggalKey)
                        ->exists();

                    // 3. JIKA BELUM ABSEN, SIMPAN DENGAN WAKTU PRESISI LENGKAP
                    if (!$sudahAbsen) {
                        Presensi::create([
                            'anggota_kelas_id' => $siswa->anggotaKelasAktif->id,
                            // toDateTimeString() akan menyimpan contoh: "2026-04-28 07:15:23"
                            'tanggal'          => $waktuMasuk->toDateTimeString(), 
                            'status'           => 'hadir',
                            'terlambat'        => $menitTerlambat > 0,
                            'menit_terlambat'  => $menitTerlambat,
                        ]);
                    }
                }
            }
        });
    }
}