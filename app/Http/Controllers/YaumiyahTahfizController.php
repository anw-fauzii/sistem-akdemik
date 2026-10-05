<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreYaumiyahTahfizRequest;
use App\Models\AngkaArab;
use App\Models\BulanSpp;
use App\Models\DaftarJilid;
use App\Models\Kelas;
use App\Models\SurahAlquran;
use App\Models\TahunAjaran;
use App\Services\YaumiyahTahfizService;
use App\Services\StatistikTahfizService;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf; // Gunakan namespace spesifik, hindari alias global 'PDF'
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class YaumiyahTahfizController extends Controller
{
    public function __construct(
        protected YaumiyahTahfizService $yaumiyahService,
        protected StatistikTahfizService $statistikService
    ) {}

    private function resolveCommonData(Request $request): array
    {
        $tahunAjaranId = $request->input('tahun_ajaran_id');
        $tahunAjaranAktif = $tahunAjaranId ? TahunAjaran::find($tahunAjaranId) : TahunAjaran::latest()->first();
        
        $listBulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaranAktif->id ?? null)
                        ->orderBy('bulan_angka', 'desc')
                        ->get();

        return [$tahunAjaranAktif, $listBulan];
    }

    public function index(): View
    {
        $grupTingkat = $this->yaumiyahService->getGrupTingkatSiswa(Auth::user()->email);
        return view('yaumiyah_tahfiz.index', compact('grupTingkat'));
    }

    public function create(Request $request, string $tingkat): View
    {
        $tanggal = $request->query('tanggal', now()->format('Y-m-d'));
        $guruNipy = Auth::user()->email;

        return view('yaumiyah_tahfiz.create', [
            'tanggal'  => $tanggal,
            'siswa'    => $this->yaumiyahService->getSiswaByTingkat($guruNipy, $tingkat),
            'yaumiyah' => $this->yaumiyahService->getExistingYaumiyah($guruNipy, $tingkat, $tanggal),
            'jilid'    => DaftarJilid::all(),
            'surah'    => SurahAlquran::all(),
            'angka'    => AngkaArab::all(),
        ]);
    }

    public function store(StoreYaumiyahTahfizRequest $request): RedirectResponse
    {
        try {
            $this->yaumiyahService->storeMassal($request->validated('records'));
            return redirect()->back()->with('success', 'Data Yaumiyah Tahfiz berhasil disimpan!');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function show(Request $request, string $id): View
    {
        $tanggalPilih = $request->query('tanggal', now()->format('Y-m-d'));
        $dataPekan = $this->yaumiyahService->getWeeklyMatrix(Auth::user()->email, $id, $tanggalPilih);

        return view('yaumiyah_tahfiz.show', $dataPekan);
    }

    public function statistik(Request $request, string $tingkat): View
    {
        [$tahunAjaranAktif, $listBulan] = $this->resolveCommonData($request);
        $statistikData = $this->statistikService->generateDashboardDataDidik(
            Auth::user()->email,
            $tingkat,
            $request->input('bulan_spp_id') ? (int) $request->input('bulan_spp_id') : null,
            $tahunAjaranAktif // PASSING OBJECT
        );

        return view('yaumiyah_tahfiz.statistik', array_merge($statistikData, [
            'listBulan'        => $listBulan,
            'tahunajaran'      => TahunAjaran::orderBy('id', 'desc')->get(),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kelas'            => null, 
        ]));
    }

    public function lengkap(Request $request, string $tingkat): View
    {
        [$tahunAjaranAktif, $listBulan] = $this->resolveCommonData($request);

        // 2. Ambil List Bulan Terkait
        $listBulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaranAktif->id ?? null)
                        ->orderBy('bulan_angka', 'desc')
                        ->get();
        // Pass default null jika tidak ada parameter spesifik
        $statistikData = $this->statistikService->generateDashboardDataLengkap($tingkat,
            $request->input('bulan_spp_id') ? (int) $request->input('bulan_spp_id') : null, $tahunAjaranAktif);
        return view('yaumiyah_tahfiz.statistik', array_merge($statistikData, [
            'listBulan'        => $listBulan,
            'tahunajaran'      => TahunAjaran::orderBy('id', 'desc')->get(),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kelas'            => null,
        ]));
    }

    public function statistikPerKelas(Request $request, int $kelasId): View
    {
        [$tahunAjaranAktif, $listBulan] = $this->resolveCommonData($request);
        $kelas = Kelas::findOrFail($kelasId);
        $statistikData = $this->statistikService->generateDashboardDataByKelas(
            $kelas,
            $request->input('bulan_spp_id') ? (int) $request->input('bulan_spp_id') : null,
            $tahunAjaranAktif
        );

        return view('yaumiyah_tahfiz.statistik', array_merge($statistikData, [
            'listBulan'        => $listBulan,
            'tahunajaran'      => TahunAjaran::orderBy('id', 'desc')->get(),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kelas'            => $kelas,
        ]));
    }

    public function print(Request $request, string $id): Response
    {
        $tanggalPilih = $request->query('tanggal', now()->format('Y-m-d'));
        $dataPrint = $this->yaumiyahService->getWeeklyMatrix(Auth::user()->email, $id, $tanggalPilih);

        // Eksekusi pemformatan teks Arab
        $dataPrint['yaumiyah'] = $this->formatArabicSurah($dataPrint['yaumiyah']);

        $pdf = Pdf::loadView('yaumiyah_tahfiz.pdf', $dataPrint)
            ->setOptions([
                'isRemoteEnabled' => true,
                'chroot'        => public_path(),
            ])
            ->setPaper('A4', 'landscape');

        return $pdf->stream("Rekap-Tahfiz-Tingkat-{$id}-{$tanggalPilih}.pdf");
    }

    /**
     * PRIVATE METHOD: Memisahkan logika formatting ArPHP dari flow utama Controller
     */
    private function formatArabicSurah(array $yaumiyahData): array
    {
        $arabic = new Arabic();

        foreach ($yaumiyahData as &$recordHarian) {
            foreach ($recordHarian as &$record) {
                $teksSurah = $record->surahAlquran->nama_surah_arab ?? '';
                
                if (trim($teksSurah) !== '') {
                    $words = explode(' ', $teksSurah);
                    $processedWords = [];
                    foreach ($words as $word) {
                        if (trim($word) !== '') {
                            $processedWords[] = @$arabic->utf8Glyphs($word);
                        }
                    }
                    $record->surah_cetak_arab = implode(' ', array_reverse($processedWords));
                } else {
                    $record->surah_cetak_arab = '-';
                }
            }
        }
        return $yaumiyahData;
    }
}