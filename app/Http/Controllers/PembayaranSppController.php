<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
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
            'bulan_spp_id' => 'required|exists:bulan_spp,id'
        ]);

        // Cari siswa berdasarkan NIS dan tahun ajaran
        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($request) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        })->where('siswa_nis', $request->siswa_nis)->first();

        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Data siswa tidak ditemukan!');
        }

        // Cek apakah pembayaran sudah ada
        if (PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('bulan_spp_id', $request->bulan_spp_id)
            ->exists()) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Tagihan ini sudah dibayar!');
        }

        // Ambil informasi kelas dan biaya
        $kelas = $anggota_kelas->kelas;
        $nominal_spp = $kelas->spp ?? 0;
        $biaya_makan = $kelas->biaya_makan ?? 0;

        // Ambil informasi bulan SPP
        $bulan_spp = BulanSpp::find($request->bulan_spp_id);
        if (!$bulan_spp) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Bulan SPP tidak valid!');
        }

        $bulan = Carbon::parse($bulan_spp->bulan_angka)->month;
        $tambahan = $bulan_spp->tambahan ?? 0;

        // Hitung jumlah sakit berturut-turut
        $sakit_beruntun = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('status', 'sakit')
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->pluck('tanggal')
            ->map(fn($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
            ->toArray();

        $max_sakit_beruntun = 0;
        $current_streak = 1;

        for ($i = 1; $i < count($sakit_beruntun); $i++) {
            $prev_date = Carbon::parse($sakit_beruntun[$i - 1]);
            $current_date = Carbon::parse($sakit_beruntun[$i]);

            if ($current_date->diffInDays($prev_date) == 1) {
                $current_streak++;
            } else {
                $max_sakit_beruntun = max($max_sakit_beruntun, $current_streak);
                $current_streak = 1;
            }
        }
        $max_sakit_beruntun = max($max_sakit_beruntun, $current_streak);

        // Potongan biaya makan
        $biaya_makan_potongan = $biaya_makan;
        if ($max_sakit_beruntun > 7) {
            $biaya_makan_potongan *= 0.75; // Maksimal potongan 25%
        }

        // Total pembayaran
        $total_pembayaran = $nominal_spp + $biaya_makan_potongan + $tambahan;

        // Simpan pembayaran
        PembayaranSpp::create([
            'anggota_kelas_id' => $anggota_kelas->id,
            'bulan_spp_id' => $request->bulan_spp_id,
            'nominal_spp' => $nominal_spp,
            'biaya_makan' => $biaya_makan_potongan,
            'tambahan' => $tambahan,
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
    
        $siswa = Siswa::where('nis', $siswa_nis)->first();
        $kelas = Kelas::find($anggota_kelas->kelas_id);
        $spp = $kelas ? $kelas->spp : 0;
        $biaya_makan = $kelas ? $kelas->biaya_makan : 0;
    
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
        ->map(function ($tagihan) use ($anggota_kelas, $biaya_makan) {
            $bulan = date('m', strtotime($tagihan->bulan_angka));
    
            // Ambil semua tanggal sakit di bulan ini
            $absen_sakit = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
                ->where('status', 'sakit')
                ->whereMonth('tanggal', $bulan)
                ->orderBy('tanggal', 'asc')
                ->pluck('tanggal')
                ->map(fn($tanggal) => Carbon::parse($tanggal));
                $potongan = 0;
                if ($absen_sakit->count() >= 7) {
                    // Pastikan tanggal sudah terurut
                    $sorted_sakit = $absen_sakit->sort()->values();
                
                    $streak = 1; // Mulai hitung streak
                    $potongan = 0; // Default tidak ada potongan
                
                    for ($i = 1; $i < $sorted_sakit->count(); $i++) {
                        $diff = $sorted_sakit[$i]->diffInDays($sorted_sakit[$i - 1]);
                
                        if ($diff == 1) { // Jika selisih 1 hari, berarti berturut-turut
                            $streak++;
                            if ($streak >= 7) { // Jika sudah mencapai 7 hari berturut-turut
                                $potongan = 0.25 * $biaya_makan;
                                break; // Maksimal 25%, jadi berhenti di sini
                            }
                        } else {
                            $streak = 1; // Reset streak jika ada jeda masuk sekolah
                        }
                    }
                }
                

            $total_biaya_makan = $biaya_makan - $potongan;
    
            $tagihan->jumlah_absen = $absen_sakit->count();
            $tagihan->potongan_makan = $potongan;
            $tagihan->total_biaya_makan = $total_biaya_makan;
    
            return $tagihan;
        });
    
        $tahun_ajaran = TahunAjaran::all();
        $siswa_list = Siswa::all();
    
        return view('pembayaran_spp.index', compact(
            'tahun_ajaran',
            'siswa',
            'siswa_list',
            'tagihan_spp',
            'tahun_ajaran_id',
            'siswa_nis',
            'spp',
            'biaya_makan'
        ));
    }
    


}
