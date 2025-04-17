<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $pekerjaan = Pekerjaan::all();
            return view('pelengkap.pekerjaan.index', compact('pekerjaan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('pelengkap.pekerjaan.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_pekerjaan' => 'required',
            ], [
                'nama_pekerjaan.required' => 'Nama Pekerjaan wajib diisi.',
            ]);
            Pekerjaan::create($validated);
            return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil disimpan');        
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $pekerjaan = Pekerjaan::findOrFail($id);
            return view('pelengkap.pekerjaan.edit', compact('pekerjaan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_pekerjaan' => 'required',
            ], [
                'nama_pekerjaan.required' => 'Nama Pekerjaan wajib diisi.',
            ]);
            $pekerjaan = Pekerjaan::findOrFail($id);
            $pekerjaan->update($validated);
            return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $pekerjaan = Pekerjaan::findOrFail($id);
            $pekerjaan->delete();
            return redirect()->route('pekerjaan.index')->with('success', 'Pekerjaan berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
