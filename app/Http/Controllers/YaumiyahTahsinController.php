<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreYaumiyahTahsinRequest;
use App\Models\AngkaArab;
use App\Models\BulanSpp;
use App\Models\DaftarJilid;
use App\Models\SurahAlquran;
use App\Models\TahunAjaran;
use App\Services\YaumiyahTahsinService;
use App\Services\StatistikTahsinService;
use ArPHP\I18N\Arabic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use PDF;

class YaumiyahTahsinController extends Controller
{
    public function __construct(
        protected YaumiyahTahsinService $yaumiyahService,
        protected StatistikTahsinService $statistikService
    ) {}

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
            // Logika penyimpanan massal dilempar ke Service
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

    public function statistik(Request $request, string $tingkat)
    {
        $tahunAjaranId = $request->input('tahun_ajaran_id');
        $bulanSppId = $request->input('bulan_spp_id');

        // 1. Dapatkan Tahun Ajaran (Jika tidak ada di request, ambil yang terbaru/aktif)
        if (!$tahunAjaranId) {
            $tahunAjaranAktif = TahunAjaran::latest()->first();
            $tahunAjaranId = $tahunAjaranAktif->id ?? null;
        } else {
            $tahunAjaranAktif = TahunAjaran::find($tahunAjaranId);
        }

        // 2. Ambil data Bulan yang HANYA milik Tahun Ajaran tersebut
        $listBulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaranId)
                        ->orderBy('bulan_angka', 'desc')
                        ->get();

        // 3. Panggil Service untuk Generate Data
        $statistikData = $this->statistikService->generateDashboardDataDidik(
            \Illuminate\Support\Facades\Auth::user()->email,
            $tingkat,
            $bulanSppId ? (int) $bulanSppId : null
        );

        // 4. Ambil semua data Tahun Ajaran untuk Dropdown pertama
        $tahunajaran = TahunAjaran::orderBy('id', 'desc')->get();

        return view('yaumiyah_tahsin.statistik', array_merge($statistikData, [
            'listBulan' => $listBulan,
            'tahunajaran' => $tahunajaran,
            'tahunAjaranAktif' => $tahunAjaranAktif, // Kirim ke view untuk default selected
        ]));
    }

    public function getBulanByTahun($tahunAjaranId)
    {
        $bulan = \App\Models\BulanSpp::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('bulan_angka', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_format' => $item->nama_bulan
                ];
            });

        return response()->json($bulan);
    }

    public function lengkap(Request $request, string $tingkat): View
    {
        $statistikData = $this->statistikService->generateDashboardDataLengkap($tingkat);

        return view('yaumiyah_tahsin.statistik', $statistikData);
    }

    public function print(Request $request, string $id)
    {
        $tanggalPilih = $request->query('tanggal', now()->format('Y-m-d'));
        
        // Panggil service matriks mingguan
        $dataPrint = $this->yaumiyahService->getWeeklyMatrix(\Illuminate\Support\Facades\Auth::user()->email, $id, $tanggalPilih);

        // --- MULAI LOGIKA KONVERSI ARABIC (ArPHP) ---
        $arabic = new Arabic();
        
        // Kita modifikasi array $yaumiyah di dalam $dataPrint
        foreach ($dataPrint['yaumiyah'] as $siswaId => &$recordHarian) {
            foreach ($recordHarian as $tanggal => &$record) {
                
                // 1. Konversi Nama Surah Arab
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
                $teksJilid = $record->daftarJilid->jilid_arab ?? '';
                if (trim($teksJilid) !== '') {
                    $words = explode(' ', $teksJilid);
                    $processedWords = [];
                    foreach ($words as $word) {
                        if (trim($word) !== '') {
                            $processedWords[] = @$arabic->utf8Glyphs($word);
                        }
                    }
                    $record->surah_jilid_arab = implode(' ', array_reverse($processedWords));
                } else {
                    $record->surah_jilid_arab = '-';
                }
                // 2. Kolom Ayat (angka_arab) sudah berupa gambar/teks dari tabel referensi
                // Jika itu teks biasa, Anda bisa menerapkan logika utf8Glyphs yang sama di sini, 
                // tapi jika tabel angka_arab Anda sudah berisi string unicode, tidak perlu di-reverse.
            }
        }
        // --- SELESAI KONVERSI ARABIC ---

        // Render PDF
        $pdf = Pdf::loadView('yaumiyah_tahsin.pdf', $dataPrint);

        // Wajib mengaktifkan opsi ini agar Font Custom bisa terbaca oleh DomPDF
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
        ]);

        $pdf->setPaper('A4', 'landscape'); 

        $namaFile = 'Rekap-Tahsin-Tingkat-'.$id.'-'.$tanggalPilih.'.pdf';
        return $pdf->stream($namaFile);
    }
}