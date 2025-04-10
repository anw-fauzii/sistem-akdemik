<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Presensi;
use App\Models\PresensiEkstrakurikuler;
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
    
        if (PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('bulan_spp_id', $request->bulan_spp_id)
            ->exists()) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Tagihan ini sudah dibayar!');
        }
    
        $kelas = $anggota_kelas->kelas;
        $nominal_spp = $kelas->spp ?? 0;
        $biaya_makan = $kelas->biaya_makan ?? 0;
    
        $bulan_spp = BulanSpp::find($request->bulan_spp_id);
        if (!$bulan_spp) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Bulan SPP tidak valid!');
        }
    
        $bulan = Carbon::parse($bulan_spp->bulan_angka)->month;
        $tambahan = $bulan_spp->tambahan ?? 0;
    
        // Hitung sakit berturut-turut
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
    
        // Potongan makan
        $biaya_makan_potongan = $biaya_makan;
        if ($max_sakit_beruntun > 7) {
            $biaya_makan_potongan *= 0.75;
        }
    
        // ✅ Tambahkan perhitungan biaya ekstrakurikuler
        $total_ekskul = 0;
    
        $ekskul_aktif = $anggota_kelas->ekstrakurikuler; // relasi di model AnggotaKelas
        foreach ($ekskul_aktif as $ekskul_item) {
            $hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $ekskul_item->id)
                ->where('status', 'hadir')
                ->whereMonth('tanggal', $bulan)
                ->exists();
    
            if ($hadir && $ekskul_item->ekstrakurikuler) {
                $total_ekskul += $ekskul_item->ekstrakurikuler->biaya ?? 0;
            }
        }
    
        $total_pembayaran = $nominal_spp + $biaya_makan_potongan + $tambahan + $total_ekskul;
    
        // Simpan pembayaran
        PembayaranSpp::create([
            'anggota_kelas_id' => $anggota_kelas->id,
            'bulan_spp_id' => $request->bulan_spp_id,
            'nominal_spp' => $nominal_spp,
            'biaya_makan' => $biaya_makan_potongan + $tambahan,
            'tambahan' => $tambahan,
            'ekstrakurikuler' => $total_ekskul,
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
    
            // Hitung absen sakit bulan ini
            $absen_sakit = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
                ->where('status', 'sakit')
                ->whereMonth('tanggal', $bulan)
                ->orderBy('tanggal', 'asc')
                ->pluck('tanggal')
                ->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal));
    
            $potongan = 0;
            if ($absen_sakit->count() >= 7) {
                $sorted = $absen_sakit->sort()->values();
                $streak = 1;
                for ($i = 1; $i < $sorted->count(); $i++) {
                    $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                    if ($diff == 1) {
                        $streak++;
                        if ($streak >= 7) {
                            $potongan = 0.25 * $biaya_makan;
                            break;
                        }
                    } else {
                        $streak = 1;
                    }
                }
            }
    
            $total_biaya_makan = $biaya_makan - $potongan;
    
            // Hitung biaya ekstrakurikuler jika hadir di bulan itu
            $biaya_ekskul_bulan_ini = $anggota_kelas->ekstrakurikuler->sum(function ($item) use ($bulan) {
                $pernah_hadir = \App\Models\PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                    ->where('status', 'hadir')
                    ->whereMonth('tanggal', $bulan)
                    ->exists();
    
                return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
            });
    
            $tagihan->jumlah_absen = $absen_sakit->count();
            $tagihan->potongan_makan = $potongan;
            $tagihan->total_biaya_makan = $total_biaya_makan;
            $tagihan->biaya_ekskul = $biaya_ekskul_bulan_ini;
    
            return $tagihan;
        });
    
        $total_ekskul = $tagihan_spp->sum('biaya_ekskul');
    
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
            'biaya_makan',
            'total_ekskul'
        ));
    }
    
}
