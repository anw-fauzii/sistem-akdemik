<?php

namespace App\Http\Controllers;

use App\Models\AnggotaJemputan;
use App\Models\BulanSpp;
use App\Models\Jemputan;
use App\Models\PembayaranJemputan;
use Illuminate\Http\Request;

class PembayaranJemputanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $bulan = BulanSpp::latest()->first();
            $jemputan = Jemputan::whereTahunAjaranId($bulan->tahun_ajaran_id)->get();
            return view('pembayaran_jemputan.index', compact('jemputan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembayaran' => 'required|array',
        ]);
        $pembayaran = $request->pembayaran;
        $bulan = BulanSpp::latest()->first();
        foreach ($pembayaran as $anggotaJemputanId => $jumlah) {
            $jumlah_bersih = (int) str_replace('.', '', $jumlah);

            PembayaranJemputan::updateOrCreate(
                [
                    'anggota_jemputan_id' => $anggotaJemputanId,
                    'bulan_spp_id' => $bulan->id
                ],
                [
                    'jumlah_bayar' => $jumlah_bersih
                ]
            );
        }

        return redirect()->route('pembayaran-jemputan.index')->with('success', 'Pembayaran berhasil disimpan');
    }


    /**
     * Display the specified resource.
     */
    public function show(PembayaranJemputan $pembayaranJemputan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembayaranJemputan $pembayaranJemputan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembayaranJemputan $pembayaranJemputan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembayaranJemputan $pembayaranJemputan)
    {
        //
    }

    public function cari(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $request->validate([
                'jemputan_id' => 'required',
            ]);

            $bulan_spp = BulanSpp::latest()->first();
            $jemputan_id = $request->jemputan_id;

            $siswa = AnggotaJemputan::with(['anggotaKelas.siswa', 'pembayaranBulan' => function($q) use ($bulan_spp) {
                $q->where('bulan_spp_id', $bulan_spp->id);
            }])->where('jemputan_id', $jemputan_id)->get();
            $jemputan = Jemputan::whereTahunAjaranId($bulan_spp->tahun_ajaran_id)->get();

            return view('pembayaran_jemputan.index', compact(
                'jemputan_id', 'siswa', 'bulan_spp', 'jemputan'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

}
