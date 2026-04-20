<?php

namespace App\Http\Controllers;

use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Services\FingerspotSyncService;
use App\Services\LaporanPresensiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanPresensiController extends Controller
{
    public function __construct(
        protected LaporanPresensiService $laporanService,
        protected FingerspotSyncService $syncService
    ) {}

    public function index(): View
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = Kelas::whereTahunAjaranId($tahunAjaran->id)->whereJenjang('SD')->get();
        
        return view('laporan.presensi.index', compact('kelas'));
    }

    /**
     * API untuk Chart/Dashboard hari ini. Dioptimasi mencegah N+1 (Super Cepat).
     */
    public function presensiHariIni(): JsonResponse
    {
        $tanggal = now()->toDateString();
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->where('jenjang', 'SD')->get();
        $kelasIds = $kelasList->pluck('id');

        // OPTIMASI: Hitung agregat langsung di level Database menggunakan JOIN
        $statistik = DB::table('anggota_kelas')
            ->join('kelas', 'anggota_kelas.kelas_id', '=', 'kelas.id')
            ->leftJoin('presensi', function ($join) use ($tanggal) {
                $join->on('anggota_kelas.id', '=', 'presensi.anggota_kelas_id')
                     ->whereDate('presensi.tanggal', '=', $tanggal);
            })
            ->whereIn('anggota_kelas.kelas_id', $kelasIds)
            ->selectRaw("
                kelas.nama_kelas,
                COUNT(anggota_kelas.id) as total_siswa,
                SUM(CASE WHEN presensi.id IS NULL THEN 1 ELSE 0 END) as belum_scan,
                SUM(CASE WHEN presensi.status IN ('alpha', 'izin', 'sakit') THEN 1 ELSE 0 END) as tidak_masuk,
                SUM(CASE WHEN presensi.status = 'hadir' THEN 1 ELSE 0 END) as sudah_scan
            ")
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->get();

        return response()->json($statistik);
    }

    public function ambilHariIni(): RedirectResponse
    {
        try {
            $this->syncService->syncToday();
            return redirect()->back()->with('success', 'Data presensi hari ini berhasil diambil dan disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyinkronkan data: ' . $e->getMessage());
        }
    }

    public function pekanan(): View
    {
        return view('laporan.presensi.pekanan');
    }

    public function bulanan(?BulanSpp $bulanSpp = null): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // 1. OPTIMASI N+1: Selalu bawa relasi Anggota Kelas agar Blade & Service tidak nge-loop query
        $kelasList = Kelas::with(['anggotaKelas'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('jenjang', 'SD')
            ->get();

        if ($kelasList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data kelas SD pada tahun ajaran ini.');
        }

        // 2. LOGIKA DEFAULT BULAN YANG CERDAS
        $bulan = $bulanSpp ?? BulanSpp::latest()->firstOrFail();
        
        // 3. KEAMANAN TANGGAL: Pastikan selalu dihitung dari awal bulan
        $tanggalAwal = \Carbon\Carbon::parse($bulan->bulan_angka)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth(); 
        
        $statistikPerKelas = [];
        foreach ($kelasList as $kelas) {
            // Karena service mengembalikan array statistik, kita gabungkan dengan data kelasnya
            $statistikPerKelas[] = [
                'kelas' => $kelas,
                ...$this->laporanService->getStatistikBulanan($kelas, $tanggalAwal, $tanggalAkhir)
            ];
        }

        return view('laporan.presensi.bulanan', [
            'bulan'             => $bulan,
            'bulan_spp'         => BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get(), // Untuk Dropdown Filter
            'statistikPerKelas' => $statistikPerKelas,
        ]);
    }
}