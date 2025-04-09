<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekstrakurikuler = Ekstrakurikuler::all();
        return view('data_master.ekstrakurikuler.index', compact('ekstrakurikuler'));
    }

    public function create()
    {
        $guru = Guru::whereStatus(true)->get();
        return view('data_master.ekstrakurikuler.create', compact('guru'));

    }

    public function store(Request $request)
    {
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
    }

    public function show($id)
    {
        $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
        $anggota_ekstrakurikuler = AnggotaEkstrakurikuler::whereEkstrakurikulerId($id)->get();
        return view('data_master.ekstrakurikuler.show', compact('ekstrakurikuler','anggota_ekstrakurikuler'));
    }

    public function edit($id)
    {
        $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
        return view('data_master.ekstrakurikuler.edit', compact('ekstrakurikuler'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $ekstrakurikuler = Ekstrakurikuler::findOrFail($id);
        $ekstrakurikuler->delete();
        return redirect()->route('ekstrakurikuler.index')->with('success', 'ekstrakurikuler berhasil dihapus');
    }
}
