<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
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
        $tahun_ajaran = TahunAjaran::latest()->first();
        $kelas = Kelas::whereTahunAjaranId($tahun_ajaran->id)->whereJenjang('SD')->get();
        return view('laporan.presensi.index', compact('kelas'));
    }

    public function presensiHariIni()
    {
        $tanggal = now()->toDateString();
        $tahunAjaran = TahunAjaran::latest()->first();

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
            ->with(['anggotaKelas.siswa'])->where('jenjang','SD')
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
            'C262C44523180D2B',
            'C262C4452319112B',
            'C262C44523201F31',
            'C262C44523270B2F',
            'C262C4452336242D',
            'C2630450C3391926'
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
                'start_date' => '2026-01-23',
                'end_date' => '2026-01-23',
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
                $menitTerlambat = max(0, $waktuMasuk->diffInMinutes(Carbon::createFromTime(7,30), false) * -1);

                Presensi::firstOrCreate(
                    ['anggota_kelas_id' => $anggota->id, 'tanggal' => $waktuMasuk],
                    [
                        'status' => "hadir",
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

    public function pekananCari(Request $request)
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
        $kelasList = Kelas::whereTahunAjaranId($tahunAjaran->id)->whereJenjang('SD')->get();
        $dataChart = [];

        $anggotaKelas = AnggotaKelas::whereIn('kelas_id', $kelasList->pluck('id'))->get()->groupBy('kelas_id');

        $presensis = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->flatten()->pluck('id'))
            ->whereBetween('tanggal', [$pekanPertama, $pekanTerakhir])
            ->get()
            ->groupBy('anggota_kelas_id');

        $dataChart = [];

        $totalTerlambatSekolah = 0;
        $terlambatPerKelas = [];

        foreach ($kelasList as $kelas) {
            $anggota = $anggotaKelas[$kelas->id] ?? collect();

            $presensiKelas = $anggota->flatMap(
                fn ($a) => $presensis[$a->id] ?? collect()
            );

            $jumlahTerlambat = $presensiKelas
                ->where('terlambat', true)
                ->count();

            $terlambatPerKelas[] = [
                'nama' => $kelas->nama_kelas,
                'jumlah' => $jumlahTerlambat,
            ];

            $totalTerlambatSekolah += $jumlahTerlambat;
        }
        $dataChart = [];

        foreach ($terlambatPerKelas as $item) {
            $persen = $totalTerlambatSekolah > 0
                ? round(($item['jumlah'] / $totalTerlambatSekolah) * 100, 1)
                : 0;

            $dataChart[] = [
                'name' => $item['nama'],
                'y' => $persen,
                'x' => $item['jumlah'], 
            ];
        }
        return view('laporan.presensi.pekanan',compact('dataChart','tahunAjaran','judul','tanggal'));
    }

    public function bulanan()
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->whereJenjang('SD')->get();

        if ($kelasList->isEmpty()) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }

        $bulan = BulanSpp::latest()->first();
        $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
        $tanggalAwal = Carbon::parse($bulan->bulan_angka);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        $statistikPerKelas = [];

        foreach ($kelasList as $kelas) {
            $statistik = $this->hitungStatistikPresensi($tanggalAwal, $tanggalAkhir, $kelas, $bulanFilter);

            $statistikPerKelas[] = [
                'kelas' => $kelas,
                ...$statistik
            ];
        }

        return view('laporan.presensi.bulanan', [
            'bulan' => $bulan,
            'bulan_spp' => BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get(),
            'statistikPerKelas' => $statistikPerKelas,
        ]);
    }

    public function bulananShow($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        if ($kelasList->isEmpty()) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }

        $bulan = BulanSpp::findOrFail($id);
        $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
        $tanggalAwal = Carbon::parse($bulan->bulan_angka);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        $statistikPerKelas = [];

        foreach ($kelasList as $kelas) {
            $statistik = $this->hitungStatistikPresensi($tanggalAwal, $tanggalAkhir, $kelas, $bulanFilter);

            $statistikPerKelas[] = [
                'kelas' => $kelas,
                ...$statistik
            ];
        }

        return view('laporan.presensi.bulanan', [
            'bulan' => $bulan,
            'bulan_spp' => BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get(),
            'statistikPerKelas' => $statistikPerKelas,
        ]);
    }


    private function hitungStatistikPresensi($tanggalAwal, $tanggalAkhir, $kelas, $bulanFilter)
    {
        $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                            ->whereMonth('tanggal', date('m', strtotime($bulanFilter)))
                            ->whereYear('tanggal', date('Y', strtotime($bulanFilter)))
                            ->get();

        $tanggal_tercatat = $presensi->pluck('tanggal')
            ->map(fn($item) => \Carbon\Carbon::parse($item)->toDateString())
            ->unique()
            ->sort()
            ->values();

        $hariEfektif = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->selectRaw('DATE(tanggal) as tgl')
            ->distinct()
            ->count();

        $totalHadir = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('status', 'hadir')
            ->count();

        $totalTepatWaktu = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('terlambat', false)
            ->count();

        if ($hariEfektif > 0) {
            $persentaseHadir = round(($totalHadir / $hariEfektif) * 100, 1);
            $persentaseTidakHadir = round(100 - $persentaseHadir, 1);
            $persentaseTepatWaktu = round(($totalTepatWaktu / $hariEfektif) * 100, 1);
            $persentaseTerlambat = round(100 - $persentaseTepatWaktu, 1);
        } else {
            $persentaseHadir = 0;
            $persentaseTidakHadir = 0;
            $persentaseTepatWaktu = 0;
            $persentaseTerlambat = 0;
        }

        return [
            'persentaseHadir' => $persentaseHadir,
            'persentaseTidakHadir' => $persentaseTidakHadir,
            'persentaseTepatWaktu' => $persentaseTepatWaktu,
            'persentaseTerlambat' => $persentaseTerlambat,
            'anggotaKelas' => $anggotaKelas,
            'presensi' => $presensi,
            'tanggal_tercatat' => $tanggal_tercatat,
        ];
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
