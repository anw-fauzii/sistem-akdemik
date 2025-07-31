<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\Kesehatan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KesehatanController extends Controller
{
    public function index(Request $request)
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                            $query->where('tahun_ajaran_id', $tahunAjaran->id);
                        })
                        ->first();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();
            $kesehatan = Kesehatan::with('bulanSpp')->whereAnggotaKelasId($anggotaKelas->id)->get();
            return view('pesertaDidik.kesehatan.index', compact('anggotaKelas', 'tahun_selama_belajar', 'kesehatan', 'tahunAjaran'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($id) {
                            $query->where('tahun_ajaran_id', $id);
                        })
                        ->first();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();
            $kesehatan = Kesehatan::with('bulanSpp')->whereAnggotaKelasId($anggotaKelas->id)->get();
            return view('pesertaDidik.kesehatan.index', compact('anggotaKelas', 'tahun_selama_belajar', 'kesehatan', 'tahunAjaran'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
