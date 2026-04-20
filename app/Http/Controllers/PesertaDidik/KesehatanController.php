<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Services\PesertaDidik\KesehatanSiswaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class KesehatanController extends Controller
{
    public function __construct(
        protected KesehatanSiswaService $service
    ) {}

    public function index(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        return $this->renderKesehatanView($tahunAjaran);
    }

    public function show(TahunAjaran $tahunAjaran): View|RedirectResponse
    {
        return $this->renderKesehatanView($tahunAjaran);
    }

    private function renderKesehatanView(TahunAjaran $tahunAjaran): View|RedirectResponse
    {
        $nis = Auth::user()->email; 
        $anggotaKelas = $this->service->getAnggotaKelasByTahun($nis, $tahunAjaran);
        if (!$anggotaKelas) {
            return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun pada tahun ajaran ini.');
        }

        return view('pesertaDidik.kesehatan.index', [
            'tahunAjaran'          => $tahunAjaran,
            'anggotaKelas'         => $anggotaKelas,
            'tahun_selama_belajar' => $this->service->getRiwayatBelajar($nis),
            'kesehatan'            => $this->service->getDataKesehatan($anggotaKelas->id),
        ]);
    }
}