<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\AnggotaKelas;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $ekstrakurikuler = Ekstrakurikuler::all();
            return view('data_master.ekstrakurikuler.index', compact('ekstrakurikuler'));
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
            
            
            $spp = new Ekstrakurikuler($validated);
            $spp->tahun_ajaran_id = $tahun->id;
            $spp->save();
            return redirect()->route('ekstrakurikuler.index')->with('success', 'ekstrakurikuler berhasil disimpan');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }        
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $tapel = TahunAjaran::latest()->first();
            $ekstrakurikuler = Ekstrakurikuler::findorfail($id);
            $anggota_ekstrakurikuler = AnggotaEkstrakurikuler::where('ekstrakurikuler_id',$id)->get();
            $siswa_belum_masuk_ekstrakurikuler = Siswa::where('ekstrakurikuler_id', null)->get();
            foreach ($siswa_belum_masuk_ekstrakurikuler as $belum_masuk_ekstrakurikuler) {
                $kelas_sebelumnya = AnggotaKelas::where('siswa_nis', $belum_masuk_ekstrakurikuler->nis)->where('tahun_ajaran_id', $tapel->id)->orderBy('id', 'ASC')->first();
                if (is_null($kelas_sebelumnya)) {
                    $belum_masuk_ekstrakurikuler->kelas_sebelumnya = null;
                    $belum_masuk_ekstrakurikuler->anggota_kelas = null;
                } else {
                    $belum_masuk_ekstrakurikuler->kelas_sebelumnya = $kelas_sebelumnya->kelas->nama_kelas;
                    $belum_masuk_ekstrakurikuler->anggota_kelas = $kelas_sebelumnya->id;
                }
            }
            return view('data_master.ekstrakurikuler.show', compact('ekstrakurikuler', 'anggota_ekstrakurikuler', 'siswa_belum_masuk_ekstrakurikuler'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
            return view('data_master.ekstrakurikuler.edit', compact('ekstrakurikuler'));
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
