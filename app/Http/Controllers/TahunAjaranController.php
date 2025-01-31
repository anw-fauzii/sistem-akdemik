<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::all();
        return view('data_master.tahun_ajaran.index', compact('tahun_ajaran'));
    }

    public function create()
    {
        return view('data_master.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tahun_ajaran' => 'required',
            'semester' => 'required',
        ], [
            'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
        ]);
        TahunAjaran::create($validated);
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $tahun_ajaran = TahunAjaran::findOrFail($id);
        return view('data_master.tahun_ajaran.edit', compact('tahun_ajaran'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $tahun_ajaran = TahunAjaran::findOrFail($id);
        $tahun_ajaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
