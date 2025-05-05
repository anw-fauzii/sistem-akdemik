<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiKelasController extends Controller
{
    public function index(Request $request)
    {
        if (user()?->hasRole('admin')) {
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
            return view('presensi_kelas.index', compact('anggotaKelas', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
            }
            $siswaList = AnggotaKelas::where('kelas_id', $kelas->id)->with('siswa')->get();
            return view('presensi_kelas.create', compact('siswaList', 'kelas'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
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
            return redirect()->route('presensi-kelas.index')->with('success', 'Presensi berhasil disimpan.');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            $bulan = BulanSpp::findOrFail($id);
            $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
            }
            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
            $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                                ->where('tanggal', 'like', "$bulanFilter%")
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort();
            return view('presensi_kelas.show', compact('bulan', 'anggotaKelas', 'presensi', 'tanggal_tercatat','bulan_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
