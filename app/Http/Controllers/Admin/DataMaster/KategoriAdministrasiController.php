<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriAdministrasi;
use Illuminate\Http\Request;

class KategoriAdministrasiController extends Controller
{
        public function index()
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriAdministrasi::all();
            return view('data_master.kategori_administrasi.index', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.kategori_administrasi.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_kategori' => 'required',
                'jenis' => 'required',
                'semester' => 'required',
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi.',
                'jenis.required' => 'Jenis kategori wajib diisi.',
                'semester.required' => 'Pilihan Semester wajib diisi.',
            ]);
            
            KategoriAdministrasi::create($validated);
            return redirect()->route('kategori-administrasi.index')->with('success', 'kategori berhasil disimpan');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }    
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriAdministrasi::findOrFail($id);
            return view('data_master.kategori_administrasi.edit', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_kategori' => 'required',
                'jenis' => 'required',
                'semester' => 'required',
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi.',
                'jenis.required' => 'Jenis kategori wajib diisi.',
                'semester.required' => 'Pilihan Semester wajib diisi.',
            ]);
            
            $kategori = KategoriAdministrasi::findOrFail($id);
            $kategori->update($validated);
            return redirect()->route('kategori-administrasi.index')->with('success', 'kategori berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = KategoriAdministrasi::findOrFail($id);
            $kategori->delete();
            return redirect()->route('kategori-administrasi.index')->with('success', 'kategori berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}