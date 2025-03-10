<?php

namespace App\Http\Controllers;

use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class BulanSppController extends Controller
{
    public function index()
    {
        $bulan_spp = BulanSpp::all();
        return view('data_master.bulan_spp.index', compact('bulan_spp'));
    }

    public function create()
    {
        return view('data_master.bulan_spp.create');
    }

    public function store(Request $request)
    {
        $tahun = TahunAjaran::latest()->first();
        $validated = $request->validate([
            'nama_bulan' => 'required',
            'bulan_angka' => 'required',
            'tambahan' => 'numeric',
        ], [
            'nama_bulan.required' => 'Nama bulan_spp wajib diisi.',
            'bulan_angka.required' => 'Tanggal bulan wajib diisi.',
            'tambahan.numeric' => 'Jumlah tambahan harus berupa angka.', 
        ]);
        
        
        $spp = new BulanSpp($validated);
        $spp->tahun_ajaran_id = $tahun->id;
        $spp->save();
        return redirect()->route('bulan-spp.index')->with('success', 'bulan_spp berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $bulan_spp = BulanSpp::findOrFail($id);
        return view('data_master.bulan_spp.edit', compact('bulan_spp'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_bulan' => 'required',
            'bulan_angka' => 'required',
            'tambahan' => 'numeric',
        ], [
            'nama_bulan.required' => 'Nama bulan_spp wajib diisi.',
            'bulan_angka.required' => 'Tanggal bulan wajib diisi.',
            'tambahan.numeric' => 'Jumlah tambahan harus berupa angka.', 
        ]);
        $bulan_spp = BulanSpp::findOrFail($id);
        $bulan_spp->update($validated);
        return redirect()->route('bulan-spp.index')->with('success', 'bulan_spp berhasil diupdate');
    }

    public function destroy($id)
    {
        $bulan_spp = BulanSpp::findOrFail($id);
        $bulan_spp->delete();
        return redirect()->route('bulan-spp.index')->with('success', 'bulan_spp berhasil dihapus');
    }
}