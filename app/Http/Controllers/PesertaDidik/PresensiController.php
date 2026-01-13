<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        if (user()?->hasRole('siswa_sd')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                            $query->where('tahun_ajaran_id', $tahunAjaran->id);
                        })
                        ->first();
            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $bulan = BulanSpp::latest()->first(); 
            $presensi = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                                ->whereMonth('tanggal', date('m', strtotime($bulan)))
                                ->whereYear('tanggal', date('Y', strtotime($bulan)))
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort()->values();
            
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            return view('pesertaDidik.presensi.index', compact('anggotaKelas', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa_sd')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            $bulan = BulanSpp::findOrFail($id);
            $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                            $query->where('tahun_ajaran_id', $tahunAjaran->id);
                        })
                        ->first();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $presensi = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                                ->where('tanggal', 'like', "$bulanFilter%")
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort();
            return view('pesertaDidik.presensi.index', compact('anggotaKelas', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
