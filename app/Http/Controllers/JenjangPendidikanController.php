<?php

namespace App\Http\Controllers;

use App\Models\JenjangPendidikan;
use Illuminate\Http\Request;

class JenjangPendidikanController extends Controller
{
    public function index()
    {
        $pendidikan = JenjangPendidikan::all();
        return view('pelengkap.pendidikan.index', compact('pendidikan'));
    }

    public function create()
    {
        return view('pelengkap.pendidikan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenjang_pendidikan' => 'required',
        ], [
            'nama_jenjang_pendidikan.required' => 'Nama pendidikan wajib diisi.',
        ]);
        JenjangPendidikan::create($validated);
        return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $pendidikan = JenjangPendidikan::findOrFail($id);
        return view('pelengkap.pendidikan.edit', compact('pendidikan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_jenjang_pendidikan' => 'required',
        ], [
            'nama_jenjang_pendidikan.required' => 'Nama pendidikan wajib diisi.',
        ]);
        $pendidikan = JenjangPendidikan::findOrFail($id);
        $pendidikan->update($validated);
        return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pendidikan = JenjangPendidikan::findOrFail($id);
        $pendidikan->delete();
        return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil dihapus');
    }
}
