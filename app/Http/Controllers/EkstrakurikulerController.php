<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\AnggotaKelas;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            if($tahun_ajaran){
                $ekstrakurikuler = Ekstrakurikuler::where('tahun_ajaran_id', $tahun_ajaran->id)->orderBy('id', 'ASC')->get();
                foreach ($ekstrakurikuler as $item) {
                    $jumlah_anggota = AnggotaEkstrakurikuler::whereEkstrakurikulerId($item->id)->count();
                    $item->jumlah_anggota = $jumlah_anggota;
                }
                return view('data_master.ekstrakurikuler.index', compact('ekstrakurikuler', 'tahun_ajaran'));
            }else{
                return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            $guru = Guru::whereStatus(true)->get();
            return view('data_master.ekstrakurikuler.create', compact('guru'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'nama_ekstrakurikuler' => 'required',
                'guru_nipy' => 'required',
                'biaya' => 'numeric',
            ], [
                'nama_ekstrakurikuler.required' => 'Nama ekstrakurikuler wajib diisi.',
                'guru_nipy.required' => 'Tanggal bulan wajib diisi.',
                'biaya.numeric' => 'Jumlah biaya harus berupa angka.', 
            ]);
            
            
            $validated['tahun_ajaran_id'] = $tahun->id;
            Ekstrakurikuler::create($validated);   
            return redirect()->route('ekstrakurikuler.index')->with('success', 'ekstrakurikuler berhasil disimpan');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }        
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $ekstrakurikuler = Ekstrakurikuler::findorfail($id);
            $anggota_ekstrakurikuler = AnggotaEkstrakurikuler::whereEkstrakurikulerId($id)->get();
            $kelas = Kelas::whereTahunAjaranId($tahun_ajaran->id)->pluck('id');
            $siswa_belum_masuk_ekstrakurikuler = AnggotaKelas::with(['siswa', 'kelas'])
                ->whereIn('kelas_id', $kelas)
                ->whereDoesntHave('anggota_ekstrakurikuler')
                ->get()
                ->map(function ($anggota) {
                    $anggota->kelas = $anggota->kelas->nama_kelas ?? null;
                    $anggota->siswa_nama = $anggota->siswa->nama_lengkap ?? '-';
                    return $anggota;
                });
            return view('data_master.ekstrakurikuler.show', compact('ekstrakurikuler', 'anggota_ekstrakurikuler', 'siswa_belum_masuk_ekstrakurikuler'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
            $guru = Guru::whereStatus(true)->get();
            return view('data_master.ekstrakurikuler.edit', compact('ekstrakurikuler','guru'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_ekstrakurikuler' => 'required',
                'guru_nipy' => 'required',
                'biaya' => 'numeric',
            ], [
                'nama_ekstrakurikuler.required' => 'Nama ekstrakurikuler wajib diisi.',
                'guru_nipy.required' => 'Tanggal bulan wajib diisi.',
                'biaya.numeric' => 'Jumlah biaya harus berupa angka.', 
            ]);
            $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
            $ekstrakurikuler->update($validated);
            return redirect()->route('ekstrakurikuler.index')->with('success', 'ekstrakurikuler berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
            $ekstrakurikuler->delete();
            return redirect()->route('ekstrakurikuler.index')->with('success', 'ekstrakurikuler berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
