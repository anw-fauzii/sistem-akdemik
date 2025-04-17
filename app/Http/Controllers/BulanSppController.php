<?php

namespace App\Http\Controllers;

use App\Models\BulanSpp;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class BulanSppController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $bulan_spp = BulanSpp::all();
            return view('data_master.bulan_spp.index', compact('bulan_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.bulan_spp.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }       
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $bulan_spp = BulanSpp::findOrFail($id);
            return view('data_master.bulan_spp.edit', compact('bulan_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $bulan_spp = BulanSpp::findOrFail($id);
            $bulan_spp->delete();
            return redirect()->route('bulan-spp.index')->with('success', 'bulan_spp berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}