<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use App\Services\PesertaDidik\PresensiSiswaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function __construct(
        protected PresensiSiswaService $service
    ) {}

    public function index(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $bulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->latest()
                    ->firstOrFail();

        return $this->renderPresensiView($tahunAjaran, $bulan);
    }

    public function show(BulanSpp $bulan): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::findOrFail($bulan->tahun_ajaran_id);
        return $this->renderPresensiView($tahunAjaran, $bulan);
    }

    private function renderPresensiView(TahunAjaran $tahunAjaran, BulanSpp $bulan): View|RedirectResponse
    {
        $nis = Auth::user()->email;
        $anggotaKelas = $this->service->getAnggotaKelasAktif($nis, $tahunAjaran);

        if (!$anggotaKelas) {
            return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
        }

        $presensi = $this->service->getPresensiByBulan($anggotaKelas->id, $bulan->bulan_angka);
        
        return view('pesertaDidik.presensi.index', [
            'anggotaKelas'     => $anggotaKelas,
            'presensi'         => $presensi,
            'tanggal_tercatat' => $presensi->pluck('tanggal')->unique()->sort()->values(),
            'bulan_spp'        => $this->service->getDaftarBulan($tahunAjaran->id),
            'bulan'            => $bulan,
        ]);
    }
}