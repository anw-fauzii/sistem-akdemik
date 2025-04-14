<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $pengumuman = Pengumuman::whereTahunAjaranId($tahun_ajaran->id)->get();
        return view('informasi.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('informasi.pengumuman.create');
    }

    public function store(Request $request)
    {
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
        
        $pengumuman = new pengumuman($validated);
        $pengumuman->tahun_ajaran_id = $tahun->id;
        $pengumuman->save();
        return redirect()->route('pengumuman.index')->with('success', 'pengumuman berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('informasi.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'pengumuman berhasil dihapus');
    }
}
