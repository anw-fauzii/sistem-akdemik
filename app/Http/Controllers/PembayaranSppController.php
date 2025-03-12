<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Presensi;
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
        $request->validate([
            'siswa_nis' => 'required',
            'tahun_ajaran_id' => 'required',
            'bulan_spp_id' => 'required'
        ]);
    
        // Cari siswa berdasarkan nis dan tahun ajaran
        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($request) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        })->where('siswa_nis', $request->siswa_nis)->first();
    
        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Data tidak ditemukan!');
        }

        $cek_pembayaran = PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)
        ->where('bulan_spp_id', $request->bulan_spp_id)
        ->exists();

        if ($cek_pembayaran) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Tagihan ini sudah dibayar!');
        }
        
        // Ambil informasi kelas dan biaya yang sesuai
        $kelas = Kelas::find($anggota_kelas->kelas_id);
        $nominal_spp = $kelas ? $kelas->spp : 0;
        $biaya_makan = $kelas ? $kelas->biaya_makan : 0;
    
        // Ambil tambahan dari bulan SPP yang dipilih
        $bulan_spp = BulanSpp::find($request->bulan_spp_id);
        $bulan = date('m', strtotime($bulan_spp->bulan_angka));
        $jumlah_absen = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('status', '!=', 'hadir')
            ->whereMonth('tanggal', $bulan)
            ->count();
        $tambahan = $bulan_spp ? $bulan_spp->tambahan : 0;

        $biaya_makan_potongan = $biaya_makan;
        if ($jumlah_absen > 7) {
            $biaya_makan_potongan *= 0.75;
        }
    
        // Total pembayaran
        $total_pembayaran = $nominal_spp + $biaya_makan_potongan + $tambahan;
    
        // Simpan pembayaran
        PembayaranSpp::create([
            'anggota_kelas_id' => $anggota_kelas->id,
            'bulan_spp_id' => $request->bulan_spp_id,
            'nominal_spp' => $nominal_spp,
            'biaya_makan' => $biaya_makan_potongan + $tambahan,
            'total_pembayaran' => $total_pembayaran,
            'keterangan' => 'Lunas'
        ]);
    
        return redirect()->route('pembayaran-spp.index')->with('success', 'Pembayaran berhasil disimpan.');
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
    public function destroy($id)
    {
        $pembayaran = PembayaranSpp::find($id);

        if (!$pembayaran) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Data pembayaran tidak ditemukan!');
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran-spp.index')->with('success', 'Pembayaran berhasil dihapus!');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required',
            'siswa_nis' => 'required'
        ]);

        $tahun_ajaran_id = $request->tahun_ajaran_id;
        $siswa_nis = $request->siswa_nis;

        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahun_ajaran_id) {
            $query->where('tahun_ajaran_id', $tahun_ajaran_id);
        })->where('siswa_nis', $siswa_nis)->first();
    
        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil informasi siswa
        $siswa = Siswa::where('nis', $siswa_nis)->first();

        // Ambil nominal SPP berdasarkan kelas siswa
        $kelas = Kelas::find($anggota_kelas->kelas_id);
        $spp = $kelas ? $kelas->spp : 0;
        $biaya_makan = $kelas ? $kelas->biaya_makan : 0;

        // Ambil semua bulan SPP dan cek status pembayaran
        $tagihan_spp = BulanSpp::leftJoin('pembayaran_spp', function ($join) use ($anggota_kelas) {
            $join->on('bulan_spp.id', '=', 'pembayaran_spp.bulan_spp_id')
                ->where('pembayaran_spp.anggota_kelas_id', '=', $anggota_kelas->id);
        })
        ->select(
            'bulan_spp.*',
            'pembayaran_spp.keterangan',
            'pembayaran_spp.id as pembayaran_id'
        )
        ->get()
        ->map(function ($tagihan) use ($anggota_kelas) {
            // Ambil bulan dari tagihan
            $bulan = date('m', strtotime($tagihan->bulan_angka)); // Ubah ke format angka (01, 02, ...)

            // Hitung jumlah absen dalam bulan tersebut
            $jumlah_absen = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
                ->where('status', '!=', 'hadir')
                ->whereMonth('tanggal', $bulan)
                ->count();

            // Tambahkan jumlah absen ke setiap tagihan
            $tagihan->jumlah_absen = $jumlah_absen;

            return $tagihan;
        });

        $tahun_ajaran = TahunAjaran::all();
        $siswa_list = Siswa::all();

        return view('pembayaran_spp.index', compact('tahun_ajaran', 'siswa', 'siswa_list', 'tagihan_spp', 'tahun_ajaran_id', 'siswa_nis', 'spp','biaya_makan'));
    }

}
