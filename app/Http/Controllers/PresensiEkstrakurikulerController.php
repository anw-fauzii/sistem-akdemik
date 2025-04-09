<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\BulanSpp;
use App\Models\Ekstrakurikuler;
use App\Models\PresensiEkstrakurikuler;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiEkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();

        if (!$ekstrakurikuler) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $anggotaEkstrakurikuler = AnggotaEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler->id)->get();
        $bulan = $request->bulan ?? now()->format('Y-m'); 
        $presensi = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $anggotaEkstrakurikuler->pluck('id'))
                            ->whereMonth('tanggal', date('m', strtotime($bulan)))
                            ->whereYear('tanggal', date('Y', strtotime($bulan)))
                            ->get();
        $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort()->values();
        $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();

        return view('presensi_ekstrakurikuler.index', compact('anggotaEkstrakurikuler', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
    }

    public function create()
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahun_ajaran->id)
                      ->where('guru_nipy', Auth::user()->email)
                      ->first();

        if (!$ekstrakurikuler) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
        }
        $siswaList = AnggotaEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler->id)->with('anggotaKelas')->get();

        return view('presensi_ekstrakurikuler.create', compact('siswaList', 'ekstrakurikuler'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'presensi' => 'required|array', 
        ]);

        foreach ($request->presensi as $anggota_ekstrakurikuler_id => $status) {
            PresensiEkstrakurikuler::updateOrCreate(
                [
                    'anggota_ekstrakurikuler_id' => $anggota_ekstrakurikuler_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('presensi-ekstrakurikuler.index')->with('success', 'Presensi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PresensiEkstrakurikuler $presensiEkstrakurikuler)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiEkstrakurikuler $presensiEkstrakurikuler)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiEkstrakurikuler $presensiEkstrakurikuler)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresensiEkstrakurikuler $presensiEkstrakurikuler)
    {
        //
    }
}
