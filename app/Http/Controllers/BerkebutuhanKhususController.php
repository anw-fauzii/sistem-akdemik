<?php

namespace App\Http\Controllers;

use App\Models\BerkebutuhanKhusus;
use Illuminate\Http\Request;

class BerkebutuhanKhususController extends Controller
{
    public function index()
    {
        $kategori = BerkebutuhanKhusus::all();
        return view('pelengkap.berkebutuhan_khusus.index', compact('kategori'));
    }

    public function create()
    {
        return view('pelengkap.berkebutuhan_khusus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_berkebutuhan_khusus' => 'required',
        ], [
            'nama_berkebutuhan_khusus.required' => 'Nama kebutuhan khusus wajib diisi.',
        ]);
        BerkebutuhanKhusus::create($validated);
        return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $kategori = BerkebutuhanKhusus::findOrFail($id);
        return view('pelengkap.berkebutuhan_khusus.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_berkebutuhan_khusus' => 'required',
        ], [
            'nama_berkebutuhan_khusus.required' => 'Nama kebutuhan khusus wajib diisi.',
        ]);
        $kategori = BerkebutuhanKhusus::findOrFail($id);
        $kategori->update($validated);
        return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = BerkebutuhanKhusus::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori-kebutuhan.index')->with('success', 'Berkebutuhan khusus berhasil dihapus');
    }
}
