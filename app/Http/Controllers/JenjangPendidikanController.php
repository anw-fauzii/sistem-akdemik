<?php

namespace App\Http\Controllers;

use App\Models\JenjangPendidikan;
use Illuminate\Http\Request;

class JenjangPendidikanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $pendidikan = JenjangPendidikan::all();
            return view('pelengkap.pendidikan.index', compact('pendidikan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('pelengkap.pendidikan.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_jenjang_pendidikan' => 'required',
            ], [
                'nama_jenjang_pendidikan.required' => 'Nama pendidikan wajib diisi.',
            ]);
            JenjangPendidikan::create($validated);
            return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil disimpan'); 
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }       
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $pendidikan = JenjangPendidikan::findOrFail($id);
            return view('pelengkap.pendidikan.edit', compact('pendidikan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_jenjang_pendidikan' => 'required',
            ], [
                'nama_jenjang_pendidikan.required' => 'Nama pendidikan wajib diisi.',
            ]);
            $pendidikan = JenjangPendidikan::findOrFail($id);
            $pendidikan->update($validated);
            return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $pendidikan = JenjangPendidikan::findOrFail($id);
            $pendidikan->delete();
            return redirect()->route('jenjang-pendidikan.index')->with('success', 'pendidikan berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
