<?php

namespace App\Http\Controllers;

use App\Exports\PresensiBulananExport;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportPdfController extends Controller
{
    public function laporanBulananExcel($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->whereJenjang('SD')->get();

        if ($kelasList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data kelas.');
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

        return Excel::download(new PresensiBulananExport($statistikPerKelas, $bulan), 'laporan-presensi-bulanan.xlsx');
    }

    public function laporanBulananPdf($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->whereJenjang('SD')->get();

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

        $pdf = Pdf::loadView('export.pdf.presensi_bulanan', [
            'bulan' => $bulan,
            'statistikPerKelas' => $statistikPerKelas,
        ]);
        return $pdf->download("laporan-presensi-bulanan-{$bulan->nama_bulan}.pdf");
    }

    public function laporanBulananKelasPdf($kelas_id, $bulan_id){
        $tahunAjaran = TahunAjaran::latest()->first();
        $bulan = BulanSpp::findOrFail($bulan_id);
        $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
        if (user()?->hasRole('guru_sd')) {
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                ->where(function ($query) {
                    $query->where('guru_nipy', Auth::user()->email)
                        ->orWhere('pendamping_nipy', Auth::user()->email);
                })->firstOrFail();
            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
            }
        }else{
            $kelas = Kelas::findOrFail($kelas_id);
        }
        $tanggalAwal = Carbon::parse($bulan->bulan_angka);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        $statistik = $this->hitungStatistikPresensi($tanggalAwal, $tanggalAkhir, $kelas, $bulanFilter);

        $pdf = Pdf::loadView('export.pdf.presensi_bulanan_kelas', [
            'bulan' => $bulan,
            'kelas' => $kelas,
            ...$statistik
        ])->setPaper('A3', 'landscape');
        return $pdf->download("laporan-presensi-bulanan-kelas-{$kelas->nama_kelas}-{$bulan->nama_bulan}.pdf");
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
}
