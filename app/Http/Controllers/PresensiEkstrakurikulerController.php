<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\BulanSpp;
use App\Models\PresensiEkstrakurikuler;
use App\Models\TahunAjaran;
use App\Http\Requests\StorePresensiEkskulRequest;
use App\Services\PresensiEkstrakurikulerService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class PresensiEkstrakurikulerController extends Controller
{
    public function __construct(
        protected PresensiEkstrakurikulerService $service
    ) {
        $this->middleware(['auth', 'role:guru_sd']);
    }

    public function index(Request $request): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $ekstrakurikuler = $this->service->getEkstrakurikulerGuru(Auth::user()->email, $tahunAjaran->id);

        abort_if(!$ekstrakurikuler, 403, 'Anda tidak mendampingi ekstrakurikuler manapun.');

        $anggotaEkstrakurikuler = AnggotaEkstrakurikuler::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->get();
        
        $bulan = $request->bulan ?? now()->format('Y-m'); 
        $parsedDate = Carbon::parse($bulan);

        // OPTIMASI: Mencari tanggal awal dan akhir bulan untuk whereBetween
        $awalBulan = $parsedDate->copy()->startOfMonth()->format('Y-m-d');
        $akhirBulan = $parsedDate->copy()->endOfMonth()->format('Y-m-d');

        $presensi = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $anggotaEkstrakurikuler->pluck('id'))
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->get();

        // ARRAY MAPPING: Menyiapkan data ke RAM untuk Blade
        $rekapPresensi = [];
        foreach ($presensi as $p) {
            $tglString = Carbon::parse($p->tanggal)->format('Y-m-d');
            $rekapPresensi[$p->anggota_ekstrakurikuler_id][$tglString] = $p;
        }

        // PERBAIKAN BUG TANGGAL BERULANG
        $tanggal_tercatat = $presensi->pluck('tanggal')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->unique()->sort()->values();

        $bulan_spp_all = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        return view('presensi_ekstrakurikuler.index', compact(
            'anggotaEkstrakurikuler', 'ekstrakurikuler', 'presensi', 'rekapPresensi', 'tanggal_tercatat', 'bulan_spp_all', 'bulan'
        ));
    }

    public function create(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $ekstrakurikuler = $this->service->getEkstrakurikulerGuru(Auth::user()->email, $tahunAjaran->id);

        abort_if(!$ekstrakurikuler, 403, 'Anda tidak mendampingi ekstrakurikuler manapun.');

        $siswaList = AnggotaEkstrakurikuler::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->get();

        return view('presensi_ekstrakurikuler.create', compact('siswaList', 'ekstrakurikuler'));
    }

    public function store(StorePresensiEkskulRequest $request): RedirectResponse
    {
        try {
            $this->service->simpanPresensiMassal(
                $request->validated('presensi'),
                $request->validated('tanggal')
            );

            return redirect()->route('presensi-ekstrakurikuler.index')->with('success', 'Presensi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function show(BulanSpp $bulanSpp): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $ekstrakurikuler = $this->service->getEkstrakurikulerGuru(Auth::user()->email, $tahunAjaran->id);

        abort_if(!$ekstrakurikuler, 403, 'Anda tidak mendampingi ekstrakurikuler manapun.');

        $anggotaEkstrakurikuler = AnggotaEkstrakurikuler::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->get();

        $parsedDate = Carbon::parse($bulanSpp->bulan_angka);
        
        $awalBulan = $parsedDate->copy()->startOfMonth()->format('Y-m-d');
        $akhirBulan = $parsedDate->copy()->endOfMonth()->format('Y-m-d');

        $presensi = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $anggotaEkstrakurikuler->pluck('id'))
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->get();

        $rekapPresensi = [];
        foreach ($presensi as $p) {
            $tglString = Carbon::parse($p->tanggal)->format('Y-m-d');
            $rekapPresensi[$p->anggota_ekstrakurikuler_id][$tglString] = $p;
        }

        $tanggal_tercatat = $presensi->pluck('tanggal')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->unique()->sort()->values();
        
        $bulan_spp_all = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
        $bulan = $bulanSpp; 

        return view('presensi_ekstrakurikuler.index', compact(
            'ekstrakurikuler', 'bulan', 'anggotaEkstrakurikuler', 'presensi', 'rekapPresensi', 'tanggal_tercatat', 'bulan_spp_all'
        ));
    }
    public function edit($tanggal): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $ekstrakurikuler = $this->service->getEkstrakurikulerGuru(Auth::user()->email, $tahunAjaran->id);

        abort_if(!$ekstrakurikuler, 403, 'Akses ditolak.');

        $siswaList = AnggotaEkstrakurikuler::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->get();

        // Tarik data presensi khusus di tanggal yang diklik
        $presensiHariIni = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $siswaList->pluck('id'))
            ->where('tanggal', $tanggal)
            ->pluck('status', 'anggota_ekstrakurikuler_id'); // Menghasilkan array: [id_siswa => 'status']

        return view('presensi_ekstrakurikuler.edit', compact('siswaList', 'ekstrakurikuler', 'tanggal', 'presensiHariIni'));
    }

    public function update(StorePresensiEkskulRequest $request, $tanggal): \Illuminate\Http\RedirectResponse
    {
        try {
            // Karena service kita menggunakan updateOrCreate, kita bisa menggunakannya untuk Edit juga!
            $this->service->simpanPresensiMassal(
                $request->validated('presensi'),
                $tanggal // Gunakan tanggal dari parameter URL yang tidak bisa diubah user
            );

            return redirect()->route('presensi-ekstrakurikuler.index')
                ->with('success', 'Presensi tanggal ' . \Carbon\Carbon::parse($tanggal)->format('d/m/Y') . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal update presensi ekskul: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }
}