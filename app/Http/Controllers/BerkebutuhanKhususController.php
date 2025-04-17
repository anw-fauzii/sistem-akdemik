<?php

namespace App\Http\Controllers;

use App\Models\BerkebutuhanKhusus;
use Illuminate\Http\Request;

class BerkebutuhanKhususController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $kategori = BerkebutuhanKhusus::all();
            return view('pelengkap.berkebutuhan_khusus.index', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('pelengkap.berkebutuhan_khusus.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_berkebutuhan_khusus' => 'required',
            ], [
                'nama_berkebutuhan_khusus.required' => 'Nama kebutuhan khusus wajib diisi.',
            ]);
            BerkebutuhanKhusus::create($validated);
            return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil disimpan');        
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = BerkebutuhanKhusus::findOrFail($id);
            return view('pelengkap.berkebutuhan_khusus.edit', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_berkebutuhan_khusus' => 'required',
            ], [
                'nama_berkebutuhan_khusus.required' => 'Nama kebutuhan khusus wajib diisi.',
            ]);
            $kategori = BerkebutuhanKhusus::findOrFail($id);
            $kategori->update($validated);
            return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $kategori = BerkebutuhanKhusus::findOrFail($id);
            $kategori->delete();
            return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
