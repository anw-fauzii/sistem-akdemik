<?php

namespace App\Http\Controllers;

use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TagihanTahunanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::whereSemester('1')->latest()->first();
            $tagihan_tahunan = TagihanTahunan::whereTahunAjaranId($tahun->id)->get();
            return view('data_master.tagihan_tahunan.index', compact('tagihan_tahunan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.tagihan_tahunan.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'jenjang' => 'required',
                'jumlah' => 'required|numeric',
                'jenis' => 'required',
            ], [
                'jenjang.required' => 'Jenjang pendidikan wajib diisi.',
                'jumlah.required' => 'Jumlah Biaya wajib diisi.',                
                'jenis.required' => 'Jenis pembayaran wajib diisi.',
                'jumlah.numeric' => 'Jumlah harus berupa angka.', 
            ]);
            
            $validated['tahun_ajaran_id'] = $tahun->id;
            $validated['kelas'] = $request->kelas;
            TagihanTahunan::create($validated);
            return redirect()->route('tagihan-tahunan.index')->with('success', 'Biaya tahunan berhasil disimpan'); 
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }       
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $tagihan_tahunan = TagihanTahunan::findOrFail($id);
            return view('data_master.tagihan_tahunan.edit', compact('tagihan_tahunan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'jenjang' => 'required',
                'jumlah' => 'required|numeric',
                'jenis' => 'required',
            ], [
                'jenjang.required' => 'Jenjang pendidikan wajib diisi.',
                'jumlah.required' => 'Jumlah Biaya wajib diisi.',
                'jenis.required' => 'Jenis pembayaran wajib diisi.', 
                'jumlah.numeric' => 'Jumlah harus berupa angka.', 
            ]);
            $tagihan_tahunan = TagihanTahunan::findOrFail($id);
            $validated['kelas'] = $request->kelas;
            $tagihan_tahunan->update($validated);
            return redirect()->route('tagihan-tahunan.index')->with('success', 'Biaya tahunan berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $tagihan_tahunan = TagihanTahunan::findOrFail($id);
            $tagihan_tahunan->delete();
            return redirect()->route('tagihan-tahunan.index')->with('success', 'Biaya tahunan berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}