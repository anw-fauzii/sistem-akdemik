<?php

namespace App\Http\Controllers;

use App\Models\Transportasi;
use Illuminate\Http\Request;

class TransportasiController extends Controller
{
    public function index()
    {
        $transportasi = Transportasi::all();
        return view('pelengkap.transportasi.index', compact('transportasi'));
    }

    public function create()
    {
        return view('pelengkap.transportasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_transportasi' => 'required',
        ], [
            'nama_transportasi.required' => 'Nama transportasi wajib diisi.',
        ]);
        Transportasi::create($validated);
        return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $transportasi = Transportasi::findOrFail($id);
        return view('pelengkap.transportasi.edit', compact('transportasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_transportasi' => 'required',
        ], [
            'nama_transportasi.required' => 'Nama transportasi wajib diisi.',
        ]);
        $transportasi = Transportasi::findOrFail($id);
        $transportasi->update($validated);
        return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $transportasi = Transportasi::findOrFail($id);
        $transportasi->delete();
        return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil dihapus');
    }
}