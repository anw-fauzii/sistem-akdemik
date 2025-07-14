<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\BulanSpp;
use App\Models\Ekstrakurikuler;
use App\Models\PresensiEkstrakurikuler;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiEkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        if (user()?->hasRole('guru')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$ekstrakurikuler) {
                return redirect()->back()->with('error', 'Anda tidak mendampingi ekstrakurikuler manapun.');
            }
            $anggotaEkstrakurikuler = AnggotaEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler->id)->get();
            $bulan = $request->bulan ?? now()->format('Y-m'); 
            $presensi = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $anggotaEkstrakurikuler->pluck('id'))
                                ->whereMonth('tanggal', date('m', strtotime($bulan)))
                                ->whereYear('tanggal', date('Y', strtotime($bulan)))
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort()->values();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            return view('presensi_ekstrakurikuler.index', compact('anggotaEkstrakurikuler', 'ekstrakurikuler', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('guru')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahun_ajaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$ekstrakurikuler) {
                return redirect()->back()->with('error', 'Anda tidak mendampingi ekstrakurikuler manapun.');
            }
            $siswaList = AnggotaEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler->id)->with('anggotaKelas')->get();
            return view('presensi_ekstrakurikuler.create', compact('siswaList', 'ekstrakurikuler'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('guru')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('guru')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            $bulan = BulanSpp::findOrFail($id);
            $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$ekstrakurikuler) {
                return redirect()->back()->with('error', 'Anda tidak mendampingi ekstrakurikuler manapun.');
            }
            $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
            $anggotaEkstrakurikuler = AnggotaEkstrakurikuler::where('ekstrakurikuler_id', $ekstrakurikuler->id)->get();
            $presensi = PresensiEkstrakurikuler::whereIn('anggota_ekstrakurikuler_id', $anggotaEkstrakurikuler->pluck('id'))
                        ->where('tanggal', 'like', "$bulanFilter%")
                        ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort();
            return view('presensi_ekstrakurikuler.show', compact('ekstrakurikuler', 'bulan', 'anggotaEkstrakurikuler', 'presensi', 'tanggal_tercatat','bulan_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        } 
    }
}
