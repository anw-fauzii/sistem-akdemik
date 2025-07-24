<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Presensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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

    public function pekanan()
    {
        return view('laporan.presensi.pekanan');
    }

    public function cari(Request $request)
    {
        $tanggal = $request->tanggal;
        $tanggalCarbon = Carbon::parse($tanggal);
        $bulan = $tanggalCarbon->month;
        $tahun = $tanggalCarbon->year;

        $tanggalAwalBulan = Carbon::create($tahun, $bulan, 1);
        $seninPertama = $tanggalAwalBulan->copy()->startOfWeek(Carbon::MONDAY);
        
        $pekanTanggal = collect();
        for ($pekan = 1; $pekan <= 5; $pekan++) {
            $start = $seninPertama->copy()->addWeeks($pekan - 1);
            $end = $start->copy()->addDays(6);


            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->month == $bulan) {
                    $pekanTanggal->put($date->format('Y-m-d'), $pekan);
                }
            }
        }

        $pekanKe = $pekanTanggal->get($tanggalCarbon->format('Y-m-d'), 1);
        $namaBulan = $tanggalCarbon->translatedFormat('F'); 

        $judul = "Bulan $namaBulan Minggu ke-$pekanKe";

        $tahunAjaran=TahunAjaran::latest()->first();
        $pekanPertama = $tanggalCarbon->copy()->startOfWeek(Carbon::MONDAY);
        $pekanTerakhir =$pekanPertama->copy()->endOfWeek();
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->get();
        $dataChart = [];

        $anggotaKelas = AnggotaKelas::whereIn('kelas_id', $kelasList->pluck('id'))->get()->groupBy('kelas_id');

        $presensis = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->flatten()->pluck('id'))
            ->whereBetween('tanggal', [$seninPertama, $pekanTerakhir])
            ->get()
            ->groupBy('anggota_kelas_id');

        $dataChart = [];

        foreach ($kelasList as $kelas) {
            $anggotaIds = $anggotaKelas[$kelas->id]->pluck('id') ?? collect();
            $presensiKelas = $anggotaIds->flatMap(fn ($id) => $presensis[$id] ?? collect());
            $hariEfektif = $presensiKelas->pluck('tanggal')->unique()->count();
            $dataTerlambat = $presensiKelas->where('terlambat', true)->count();

            $totalTerlambat = $hariEfektif > 0
                ? round(($dataTerlambat / $hariEfektif) * 100, 1)
                : 0;

            $dataChart[] = [
                'name' => $kelas->nama_kelas,
                'y' => $totalTerlambat,
                'x' => $dataTerlambat,
            ];
        }
        return view('laporan.presensi.pekanan',compact('dataChart','tahunAjaran','judul','tanggal'));
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
