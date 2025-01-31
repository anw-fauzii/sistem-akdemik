<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index()
    {
        $pekerjaan = Pekerjaan::all();
        return view('pelengkap.pekerjaan.index', compact('pekerjaan'));
    }

    public function create()
    {
        return view('pelengkap.pekerjaan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pekerjaan' => 'required',
        ], [
            'nama_pekerjaan.required' => 'Nama Pekerjaan wajib diisi.',
        ]);
        Pekerjaan::create($validated);
        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $pekerjaan = Pekerjaan::findOrFail($id);
        return view('pelengkap.pekerjaan.edit', compact('pekerjaan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pekerjaan' => 'required',
        ], [
            'nama_pekerjaan.required' => 'Nama Pekerjaan wajib diisi.',
        ]);
        $pekerjaan = Pekerjaan::findOrFail($id);
        $pekerjaan->update($validated);
        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pekerjaan = Pekerjaan::findOrFail($id);
        $pekerjaan->delete();
        return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil dihapus');
    }
}
