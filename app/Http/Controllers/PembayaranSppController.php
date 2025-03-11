<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PembayaranSppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun_ajaran = TahunAjaran::all();
        $siswa_list = Siswa::where('kelas_id','!=',NULL)->get();
        return view('pembayaran_spp.index', compact('tahun_ajaran', 'siswa_list'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PembayaranSpp $pembayaranSpp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembayaranSpp $pembayaranSpp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembayaranSpp $pembayaranSpp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembayaranSpp $pembayaranSpp)
    {
        //
    }
    public function cari(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required',
            'siswa_nis' => 'required'
        ]);

        $tahun_ajaran_id = $request->tahun_ajaran_id;
        $siswa_nis = $request->siswa_nis;

        // Cari anggota kelas berdasarkan tahun ajaran dan siswa
        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        })->where('siswa_nis', $siswa_nis)->first();

        if (!$anggota_kelas) {
            return redirect()->route('pembayaran.spp')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil informasi siswa
        $siswa = Siswa::where('nis', $siswa_nis)->first();

        // Ambil tagihan SPP berdasarkan anggota_kelas_id
        $tagihan_spp = PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)->get();

        $tahun_ajaran = TahunAjaran::all();
        $siswa_list = Siswa::all();

        return view('pembayaran_spp.index', compact('tahun_ajaran', 'siswa', 'siswa_list', 'tagihan_spp', 'tahun_ajaran_id', 'siswa_nis'));
    }

}
