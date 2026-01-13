<?php

namespace App\Http\Controllers\Admin\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $pengumuman = Pengumuman::whereTahunAjaranId($tahun_ajaran->id)->get();
            return view('informasi.pengumuman.index', compact('pengumuman'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('informasi.pengumuman.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'judul' => 'required',
                'isi' => 'required',
                'tanggal' => 'required|date',
            ], [
                'judul.required' => 'Nama pengumuman wajib diisi.',
                'isi.required' => 'Tanggal bulan wajib diisi.',
                'tanggal.required' => 'Jumlah unit harus berupa angka.', 
            ]);
            
            $validated['tahun_ajaran_id'] = $tahun->id;
            Pengumuman::create($validated);
            return redirect()->route('pengumuman.index')->with('success', 'pengumuman berhasil disimpan');  
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }      
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $pengumuman = Pengumuman::findOrFail($id);
            return view('informasi.pengumuman.edit', compact('pengumuman'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'judul' => 'required',
                'isi' => 'required',
                'tanggal' => 'required|date',
            ], [
                'judul.required' => 'Nama pengumuman wajib diisi.',
                'isi.required' => 'Tanggal bulan wajib diisi.',
                'tanggal.required' => 'Jumlah unit harus berupa angka.', 
            ]);
            $pengumuman = Pengumuman::findOrFail($id);
            $pengumuman->update($validated);
            return redirect()->route('pengumuman.index')->with('success', 'pengumuman berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $pengumuman = Pengumuman::findOrFail($id);
            $pengumuman->delete();
            return redirect()->route('pengumuman.index')->with('success', 'pengumuman berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
