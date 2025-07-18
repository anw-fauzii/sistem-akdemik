<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

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


    public function simpanNantiButuhdatapresensiHariIni()
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
            $rekap = [
                'kelas' => $kelas->nama_kelas,
                'sudah_scan' => [],
                'tidak_masuk' => [],
                'belum_scan' => [],
            ];

            foreach ($kelas->anggotaKelas as $anggota) {
                $presensi = $presensiHariIni->get($anggota->id);

                if ($presensi) {
                    if (in_array($presensi->status, ['alpha', 'izin', 'sakit'])) {
                        $rekap['tidak_masuk'][] = $anggota->siswa->nama_lengkap;
                    } else {
                        $rekap['sudah_scan'][] = $anggota->siswa->nama_lengkap;
                    }
                } else {
                    $rekap['belum_scan'][] = $anggota->siswa->nama_lengkap;
                }
            }

            $hasil[] = $rekap;
        }

        return response()->json($hasil);
    }
}
