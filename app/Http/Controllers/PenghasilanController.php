<?php

namespace App\Http\Controllers;

use App\Models\Penghasilan;
use Illuminate\Http\Request;

class PenghasilanController extends Controller
{
    public function index()
    {
        $penghasilan = Penghasilan::all();
        return view('pelengkap.penghasilan.index', compact('penghasilan'));
    }

    public function create()
    {
        return view('pelengkap.penghasilan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penghasilan' => 'required',
        ], [
            'nama_penghasilan.required' => 'Nama penghasilan wajib diisi.',
        ]);
        Penghasilan::create($validated);
        return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        return view('pelengkap.penghasilan.edit', compact('penghasilan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_penghasilan' => 'required',
        ], [
            'nama_penghasilan.required' => 'Nama penghasilan wajib diisi.',
        ]);
        $penghasilan = Penghasilan::findOrFail($id);
        $penghasilan->update($validated);
        return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil diupdate');
    }

    public function destroy($id)
    {
        $penghasilan = Penghasilan::findOrFail($id);
        $penghasilan->delete();
        return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil dihapus');
    }
}
