<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LaporanPresensiController extends Controller
{
    public function index()
    {
        return view('laporan.presensi.index');
    }

    public function presensiHariIni()
    {
        $tanggal = now()->toDateString();
        $tahunAjaran = TahunAjaran::latest()->first();

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
            ->with(['anggotaKelas.siswa'])
            ->get();

        $presensiHariIni = Presensi::whereDate('tanggal', $tanggal)->get()->keyBy(function ($item) {
            return $item->anggota_kelas_id;
        });

        $hasil = [];

        foreach ($kelasList as $kelas) {
            $sudahScan = 0;
            $tidakMasuk = 0;
            $belumScan = 0;

            foreach ($kelas->anggotaKelas as $anggota) {
                $presensi = $presensiHariIni->get($anggota->id);

                if ($presensi) {
                    if (in_array($presensi->status, ['alpha', 'izin', 'sakit'])) {
                        $tidakMasuk++;
                    } else {
                        $sudahScan++;
                    }
                } else {
                    $belumScan++;
                }
            }

            $hasil[] = [
                'kelas' => $kelas->nama_kelas,
                'sudah_scan' => $sudahScan,
                'belum_scan' => $belumScan,
                'tidak_masuk' => $tidakMasuk,
            ];
        }

        return response()->json($hasil);
    }

    public function ambilHariIni()
    {
        $cloud_ids = [
            'C2630450C30A1D24',
        ];

        $api_token = config('services.fingerspot.api_token');
        $today = Carbon::today()->format('Y-m-d');

        $allLogs = [];

        foreach ($cloud_ids as $cloud_id) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type' => 'application/json',
            ])->post('https://developer.fingerspot.io/api/get_attlog', [
                'trans_id' => uniqid(),
                'cloud_id' => $cloud_id,
                'start_date' => $today,
                'end_date' => $today,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $logs = $data['data'] ?? [];
                foreach ($logs as $log) {
                    $nis = $log['pin'];
                    $waktu = Carbon::parse($log['scan_date']);
                    $tanggal = $waktu->toDateString();

                    if (!isset($allLogs[$nis][$tanggal]) || $waktu->lt($allLogs[$nis][$tanggal])) {
                        $allLogs[$nis][$tanggal] = $waktu;
                    }
                }
            }
        }

        foreach ($allLogs as $nis => $tanggalList) {
            $siswa = Siswa::where('nis', $nis)->first();
            if (!$siswa) continue;

            $anggota = $siswa->anggotaKelasAktif; 

            foreach ($tanggalList as $tanggal => $waktuMasuk) {
                $menitTerlambat = max(0, $waktuMasuk->diffInMinutes(Carbon::createFromTime(7, 15), false) * -1);
                $status = $menitTerlambat > 0 ? '1' : '0';

                Presensi::firstOrCreate(
                    ['anggota_kelas_id' => $anggota->id, 'tanggal' => $waktuMasuk],
                    [
                        'status' => $status,
                        'terlambat' => $menitTerlambat > 0,
                        'menit_terlambat' => $menitTerlambat,
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Data presensi hari ini berhasil diambil dan disimpan.');
    }


    // public function simpanNantiButuhdatapresensiHariIni()
    // {
    //     $tanggal = now()->toDateString();
    //     $tahunAjaran = TahunAjaran::latest()->first();

    //     $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
    //     ->with(['anggotaKelas.siswa'])
    //     ->get();
    //     $presensiHariIni = Presensi::whereDate('tanggal', $tanggal)->get()->keyBy(function ($item) {
    //         return $item->anggota_kelas_id;
    //     });

    //     $hasil = [];

    //     foreach ($kelasList as $kelas) {
    //         $rekap = [
    //             'kelas' => $kelas->nama_kelas,
    //             'sudah_scan' => [],
    //             'tidak_masuk' => [],
    //             'belum_scan' => [],
    //         ];

    //         foreach ($kelas->anggotaKelas as $anggota) {
    //             $presensi = $presensiHariIni->get($anggota->id);

    //             if ($presensi) {
    //                 if (in_array($presensi->status, ['alpha', 'izin', 'sakit'])) {
    //                     $rekap['tidak_masuk'][] = $anggota->siswa->nama_lengkap;
    //                 } else {
    //                     $rekap['sudah_scan'][] = $anggota->siswa->nama_lengkap;
    //                 }
    //             } else {
    //                 $rekap['belum_scan'][] = $anggota->siswa->nama_lengkap;
    //             }
    //         }

    //         $hasil[] = $rekap;
    //     }

    //     return response()->json($hasil);
    // }
}
