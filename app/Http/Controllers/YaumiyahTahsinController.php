<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreYaumiyahTahsinRequest;
use App\Models\AngkaArab;
use App\Models\BulanSpp;
use App\Models\DaftarJilid;
use App\Models\Kelas;
use App\Models\SurahAlquran;
use App\Models\TahunAjaran;
use App\Services\YaumiyahTahsinService;
use App\Services\StatistikTahsinService;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class YaumiyahTahsinController extends Controller
{
    public function __construct(
        protected YaumiyahTahsinService $yaumiyahService,
        protected StatistikTahsinService $statistikService
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
        return view('yaumiyah_tahsin.index', compact('grupTingkat'));
    }

    public function create(Request $request, string $tingkat): View
    {
        $tanggal = $request->query('tanggal', now()->format('Y-m-d'));
        $guruNipy = Auth::user()->email;

        return view('yaumiyah_tahsin.create', [
            'tanggal'  => $tanggal,
            'siswa'    => $this->yaumiyahService->getSiswaByTingkat($guruNipy, $tingkat),
            'yaumiyah' => $this->yaumiyahService->getExistingYaumiyah($guruNipy, $tingkat, $tanggal),
            'jilid'    => DaftarJilid::all(),
            'surah'    => SurahAlquran::all(),
            'angka'    => AngkaArab::all(),
        ]);
    }

    public function store(StoreYaumiyahTahsinRequest $request): RedirectResponse
    {
        try {
            $this->yaumiyahService->storeMassal($request->validated('records'));
            return redirect()->back()->with('success', 'Data Yaumiyah Tahsin berhasil disimpan!');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function show(Request $request, string $id): View
    {
        $tanggalPilih = $request->query('tanggal', now()->format('Y-m-d'));
        $dataPekan = $this->yaumiyahService->getWeeklyMatrix(Auth::user()->email, $id, $tanggalPilih);

        return view('yaumiyah_tahsin.show', $dataPekan);
    }

    public function statistik(Request $request, string $tingkat): View
    {
        [$tahunAjaranAktif, $listBulan] = $this->resolveCommonData($request);
        $statistikData = $this->statistikService->generateDashboardDataDidik(
            Auth::user()->email,
            $tingkat,
            $request->input('bulan_spp_id') ? (int) $request->input('bulan_spp_id') : null,
            $tahunAjaranAktif
        );

        return view('yaumiyah_tahsin.statistik', array_merge($statistikData, [
            'listBulan'        => $listBulan,
            'tahunajaran'      => TahunAjaran::orderBy('id', 'desc')->get(),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kelas'            => null, 
        ]));
    }

    public function getBulanByTahun(string $tahunAjaranId): JsonResponse
    {
        $bulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('bulan_angka', 'desc')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'nama_format' => $item->nama_bulan
            ]);

        return response()->json($bulan);
    }

    public function lengkap(Request $request, string $tingkat): View
    {
        [$tahunAjaranAktif, $listBulan] = $this->resolveCommonData($request);

        $statistikData = $this->statistikService->generateDashboardDataLengkap(
            $tingkat,
            $request->input('bulan_spp_id') ? (int) $request->input('bulan_spp_id') : null,
            $tahunAjaranAktif
        );

        return view('yaumiyah_tahsin.statistik', array_merge($statistikData, [
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

        return view('yaumiyah_tahsin.statistik', array_merge($statistikData, [
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
        $dataPrint['yaumiyah'] = $this->formatArabicTexts($dataPrint['yaumiyah']);

        $pdf = Pdf::loadView('yaumiyah_tahsin.pdf', $dataPrint)
            ->setOptions([
                'isRemoteEnabled' => true,
                'chroot'          => public_path(),
            ])
            ->setPaper('A4', 'landscape'); 

        return $pdf->stream("Rekap-Tahsin-Tingkat-{$id}-{$tanggalPilih}.pdf");
    }

    /**
     * PRIVATE METHOD: Ekstrak logika ArPHP.
     */
    private function formatArabicTexts(array $yaumiyahData): array
    {
        $arabic = new Arabic();

        foreach ($yaumiyahData as &$recordHarian) {
            foreach ($recordHarian as &$record) {
                
                $teksSurah = $record->surahAlquran->nama_surah_arab ?? '';
                $record->surah_cetak_arab = $this->convertArabicString($arabic, $teksSurah);

                $teksJilid = $record->daftarJilid->jilid_arab ?? '';
                $record->surah_jilid_arab = $this->convertArabicString($arabic, $teksJilid);
            }
        }
        return $yaumiyahData;
    }

    /**
     * PRIVATE HELPER: Mencegah duplikasi explode() dan array_reverse()
     */
    private function convertArabicString(Arabic $arabic, string $text): string
    {
        if (trim($text) === '') {
            return '-';
        }

        $words = explode(' ', $text);
        $processedWords = [];
        foreach ($words as $word) {
            if (trim($word) !== '') {
                $processedWords[] = @$arabic->utf8Glyphs($word);
            }
        }
        return implode(' ', array_reverse($processedWords));
    }
}