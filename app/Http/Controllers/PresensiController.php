<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
        $bulan = $request->bulan ?? now()->format('Y-m'); 
        $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                            ->whereMonth('tanggal', date('m', strtotime($bulan)))
                            ->whereYear('tanggal', date('Y', strtotime($bulan)))
                            ->get();
        $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort()->values();
        $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        return view('presensi.index', compact('anggotaKelas', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
    }

    public function create()
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)
                      ->where('guru_nipy', Auth::user()->email)
                      ->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
        }
        $siswaList = AnggotaKelas::where('kelas_id', $kelas->id)->with('siswa')->get();

        return view('presensi.create', compact('siswaList', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'presensi' => 'required|array', 
        ]);

        foreach ($request->presensi as $anggota_kelas_id => $status) {
            Presensi::updateOrCreate(
                [
                    'anggota_kelas_id' => $anggota_kelas_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
    }

    public function show($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();

        $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
        $bulan = BulanSpp::findOrFail($id);
        $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
        $anggotaKelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahunAjaran) {
            $query->where('tahun_ajaran_id', $tahunAjaran->id);
        })->get();
        $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                            ->where('tanggal', 'like', "$bulanFilter%")
                            ->get();
        $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort();
        return view('presensi.show', compact('bulan', 'anggotaKelas', 'presensi', 'tanggal_tercatat','bulan_spp'));
    }

    public function edit(Presensi $presensi)
    {
        //
    }

    public function update(Request $request, Presensi $presensi)
    {
        //
    }

    public function destroy(Presensi $presensi)
    {
        //
    }
}
