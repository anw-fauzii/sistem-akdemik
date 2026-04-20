<?php

namespace App\Http\Controllers\TK;

use App\Http\Controllers\Controller;
use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreKesehatanRequest;
use App\Services\TK\TkKesehatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class KesehatanController extends Controller
{
    public function __construct(
        protected TkKesehatanService $service
    ) {}

    public function index(?BulanSpp $bulanSpp = null): View|RedirectResponse
    {
        $tahunAjaran  = TahunAjaran::latest()->firstOrFail();

        $kelas = $this->service->getKelasGuruAktif(Auth::user()->email, $tahunAjaran->id);

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun pada tahun ajaran ini.');
        }

        $bulanTerbaru = $bulanSpp ?? BulanSpp::latest()->firstOrFail();
        $bulan_spp    = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $anggotaKelasList = $this->service->getAnggotaDenganKesehatan($kelas->id, $bulanTerbaru->id);

        return view('tk.kesehatan.index', compact('bulan_spp', 'anggotaKelasList', 'bulanTerbaru', 'kelas'));
    }

    public function edit(BulanSpp $bulanSpp): View|RedirectResponse
    {
        // 1. Ambil Tahun Ajaran
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // 2. Lempar ID-nya
        $kelas = $this->service->getKelasGuruAktif(Auth::user()->email, $tahunAjaran->id);

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }

        $anggotaKelasList = $this->service->getAnggotaDenganKesehatan($kelas->id, $bulanSpp->id);
        $semuaKosong = $anggotaKelasList->every(fn($anggota) => $anggota->dataKesehatan === null);

        return view('tk.kesehatan.create', compact('anggotaKelasList', 'bulanSpp', 'semuaKosong', 'kelas'));
    }

    public function store(StoreKesehatanRequest $request): RedirectResponse
    {
        $this->service->simpanDataMassal($request->validated(), $request->bulan_spp_id);

        // 1. Ambil Tahun Ajaran
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // 2. Lempar ID-nya
        $kelas = $this->service->getKelasGuruAktif(Auth::user()->email, $tahunAjaran->id);

        if ($kelas) {
            return redirect()->route('data-kesehatan.index')->with('success', 'Data kesehatan berhasil disimpan.');
        }

        return redirect()->route('kelas.pgtk.show.kelas', $request->bulan_spp_id)
                        ->with('success', 'Data kesehatan berhasil disimpan.');
    }
}