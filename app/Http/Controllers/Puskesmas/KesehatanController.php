<?php

namespace App\Http\Controllers\Puskesmas;

use App\Http\Controllers\Controller;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Services\Puskesmas\PuskesmasKesehatanService;
use Illuminate\View\View;

class KesehatanController extends Controller
{
    public function __construct(
        protected PuskesmasKesehatanService $service
    ) {}

    public function indexKelas(?BulanSpp $bulanSpp = null): View
    {
        $tahunAjaran  = TahunAjaran::latest()->firstOrFail();
        $bulanTerbaru = $bulanSpp ?? BulanSpp::latest()->firstOrFail();
        $bulan_spp    = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $progresKesehatan = $this->service->getProgresKesehatan($tahunAjaran, $bulanTerbaru);

        return view('tk.puskesmas.index', compact('bulan_spp', 'bulanTerbaru', 'progresKesehatan'));
    }

    public function detailKelas(BulanSpp $bulanSpp, Kelas $kelas): View
    {
        $tahunAjaran  = TahunAjaran::latest()->firstOrFail();
        $bulan_spp    = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
        
        $anggotaKelasList = $this->service->getAnggotaDenganKesehatan($kelas, $bulanSpp);
        
        $dataKesehatan = $anggotaKelasList->pluck('dataKesehatan')->filter()->keyBy('anggota_kelas_id');

        return view('tk.puskesmas.detail', [
            'bulanTerbaru'     => $bulanSpp,
            'kelas'            => $kelas,
            'bulan_spp'        => $bulan_spp,
            'anggotaKelasList' => $anggotaKelasList,
            'dataKesehatan'    => $dataKesehatan
        ]);
    }

    public function editKelas(BulanSpp $bulanSpp, Kelas $kelas): View
    {
        $anggotaKelasList = $this->service->getAnggotaDenganKesehatan($kelas, $bulanSpp);
        $semuaKosong = $anggotaKelasList->every(fn($anggota) => $anggota->dataKesehatan === null);
        return view('tk.puskesmas.create', compact('anggotaKelasList', 'bulanSpp', 'kelas', 'semuaKosong'));
    }
}