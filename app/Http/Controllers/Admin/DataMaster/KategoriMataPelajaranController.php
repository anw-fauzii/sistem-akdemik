<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriMataPelajaran;
use Illuminate\Http\Request;

class KategoriMataPelajaranController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriMataPelajaran::all();
            return view('mapel.kategori.index', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('mapel.kategori.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'kategori' => 'required',
            ], [
                'kategori.required' => 'Nama kategori wajib diisi.',
            ]);
            
            KategoriMataPelajaran::create($validated);
            return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'kategori berhasil disimpan');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }    
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriMataPelajaran::findOrFail($id);
            return view('mapel.kategori.edit', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'kategori' => 'required',
            ], [
                'kategori.required' => 'Nama kategori wajib diisi.',
            ]);
            $kategori = KategoriMataPelajaran::findOrFail($id);
            $kategori->update($validated);
            return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'kategori berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriMataPelajaran::findOrFail($id);
            $kategori->delete();
            return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'kategori berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}