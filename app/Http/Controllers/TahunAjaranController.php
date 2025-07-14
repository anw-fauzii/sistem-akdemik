<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::all();
            return view('data_master.tahun_ajaran.index', compact('tahun_ajaran'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.tahun_ajaran.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_tahun_ajaran' => 'required',
                'semester' => 'required',
            ], [
                'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
                'semester.required' => 'Semester wajib diisi.',
            ]);
            TahunAjaran::create($validated);
            Siswa::whereStatus(TRUE)
                ->update([
                    'kelas_id' => null,
                    'guru_nipy' => null,
                    'ekstrakurikuler_id' => null
                ]);
            return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil disimpan');     
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }   
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::findOrFail($id);
            return view('data_master.tahun_ajaran.edit', compact('tahun_ajaran'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_tahun_ajaran' => 'required',
                'semester' => 'required',
            ], [
                'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
                'semester.required' => 'Semester wajib diisi.',
            ]);
            $tahun_ajaran = TahunAjaran::findOrFail($id);
            $tahun_ajaran->update($validated);
            return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::findOrFail($id);
            if ($tahun_ajaran->kelas->count() > 0) {
                return redirect()->route('tahun-ajaran.index')->with('error', 'Tahun ajaran tidak bisa dihapus karena masih memiliki kelas.');
            }
            $tahun_ajaran->delete();
            Siswa::whereStatus(TRUE)
                ->update([
                    'kelas_id' => null,
                    'guru_nipy' => null,
                    'ekstrakurikuler_id' => null
                ]);
            return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
