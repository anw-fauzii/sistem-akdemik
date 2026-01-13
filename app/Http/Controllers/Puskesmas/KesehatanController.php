<?php

namespace App\Http\Controllers\Puskesmas;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Kesehatan;
use App\Models\TahunAjaran;

class KesehatanController extends Controller
{
    public function indexKelas()
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $bulanTerbaru = BulanSpp::latest()->first();
        $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $kelasList = Kelas::with(['anggotaKelas.siswa'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->get();

        $dataKesehatan = Kesehatan::where('bulan_spp_id', $bulanTerbaru->id ?? null)->get();

        $progresKesehatan = $kelasList->map(function ($kelas) use ($dataKesehatan) {
            $anggotaIds = $kelas->anggotaKelas->pluck('id');

            $totalSiswa = $anggotaIds->count();
            $sudahIsi = $dataKesehatan->whereIn('anggota_kelas_id', $anggotaIds)->count();
            $persen = $totalSiswa > 0 ? round(($sudahIsi / $totalSiswa) * 100) : 0;

            return [
                'kelas' => $kelas,
                'total' => $totalSiswa,
                'terisi' => $sudahIsi,
                'persen' => $persen,
            ];
        });
        return view('tk.puskesmas.index', compact('kelasList', 'bulan_spp', 'bulanTerbaru', 'progresKesehatan'));
    }

    public function showKelas($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $bulanTerbaru = BulanSpp::findOrFail($id);
        $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        $kelasList = Kelas::with(['anggotaKelas.siswa'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->get();

        $dataKesehatan = Kesehatan::where('bulan_spp_id', $bulanTerbaru->id ?? null)->get();

        $progresKesehatan = $kelasList->map(function ($kelas) use ($dataKesehatan) {
            $anggotaIds = $kelas->anggotaKelas->pluck('id');

            $totalSiswa = $anggotaIds->count();
            $sudahIsi = $dataKesehatan->whereIn('anggota_kelas_id', $anggotaIds)->count();
            $persen = $totalSiswa > 0 ? round(($sudahIsi / $totalSiswa) * 100) : 0;

            return [
                'kelas' => $kelas,
                'total' => $totalSiswa,
                'terisi' => $sudahIsi,
                'persen' => $persen,
            ];
        });
        return view('tk.puskesmas.index', compact('kelasList', 'bulan_spp', 'bulanTerbaru', 'progresKesehatan'));
    }

    public function detailKelas($bulan_spp_id, $kelas_id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::findOrFail($kelas_id);
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $anggotaKelasList = AnggotaKelas::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->get();
        $bulanTerbaru = BulanSpp::findOrFail($bulan_spp_id);
        $bulan_spp = BulanSpp::whereTahunAjaranId($tahunAjaran->id)->get();
        $dataKesehatan = Kesehatan::where('bulan_spp_id', $bulanTerbaru->id ?? null)
            ->whereIn('anggota_kelas_id', $anggotaKelasList->pluck('id'))
            ->get()
            ->keyBy('anggota_kelas_id');
        return view('tk.puskesmas.detail', compact('bulan_spp','anggotaKelasList','dataKesehatan','bulanTerbaru', 'kelas'));
    }

    public function editKelas($bulan_spp_id, $kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas Tidak ditemukan.');
        }
        $bulanTerbaru = BulanSpp::findOrFail($bulan_spp_id);
        $anggotaKelasList = AnggotaKelas::with(['siswa', 'dataKesehatan' => function ($query) use ($bulanTerbaru) {
            $query->where('bulan_spp_id', $bulanTerbaru->id);
        }])->where('kelas_id', $kelas->id)->get();
        $semuaKosong = $anggotaKelasList->every(function ($anggota) {
            return $anggota->dataKesehatan === null;
        });
        return view('tk.puskesmas.create', compact('anggotaKelasList','bulanTerbaru','semuaKosong','kelas'));
    }
}
