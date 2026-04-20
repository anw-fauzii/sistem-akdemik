<?php

namespace App\Http\Controllers;

use App\Exports\PresensiBulananExport;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Services\PresensiReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportPdfController extends Controller
{
    public function __construct(
        protected PresensiReportService $reportService
    ) {}

    public function laporanBulananExcel(BulanSpp $bulanSpp)
    {
        try {
            $statistikPerKelas = $this->reportService->generateStatistikSemuaKelas($bulanSpp);
            
            return Excel::download(
                new PresensiBulananExport($statistikPerKelas, $bulanSpp), 
                "laporan-presensi-bulanan-{$bulanSpp->nama_bulan}.xlsx"
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function laporanBulananPdf(BulanSpp $bulanSpp)
    {
        try {
            $statistikPerKelas = $this->reportService->generateStatistikSemuaKelas($bulanSpp);

            $pdf = Pdf::loadView('export.pdf.presensi_bulanan', [
                'bulan'             => $bulanSpp,
                'statistikPerKelas' => $statistikPerKelas,
            ]);
            
            return $pdf->download("laporan-presensi-bulanan-{$bulanSpp->nama_bulan}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function laporanBulananKelasPdf(Kelas $kelas, BulanSpp $bulanSpp)
    {
        // Proteksi Akses Guru
        if (user()?->hasRole('guru_sd')) {
            $tahunAjaran = TahunAjaran::latest()->firstOrFail();
            abort_if(
                $kelas->tahun_ajaran_id !== $tahunAjaran->id || 
                ($kelas->guru_nipy !== Auth::user()->email && $kelas->pendamping_nipy !== Auth::user()->email),
                403, 'Akses ditolak. Anda tidak mengajar kelas ini.'
            );
        }

        $tanggalAwal = Carbon::parse($bulanSpp->bulan_angka)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        // Load anggota dan siswa untuk Blade PDF
        $kelas->load('anggotaKelas.siswa');

        $statistik = $this->reportService->hitungStatistik($kelas, $tanggalAwal, $tanggalAkhir);

        $pdf = Pdf::loadView('export.pdf.presensi_bulanan_kelas', [
            'bulan' => $bulanSpp,
            'kelas' => $kelas,
            ...$statistik
        ])->setPaper('A3', 'landscape');
        
        return $pdf->download("laporan-presensi-bulanan-{$kelas->nama_kelas}-{$bulanSpp->nama_bulan}.pdf");
    }
}