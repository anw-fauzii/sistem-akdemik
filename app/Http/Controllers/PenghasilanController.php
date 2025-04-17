<?php

namespace App\Http\Controllers;

use App\Models\Penghasilan;
use Illuminate\Http\Request;

class PenghasilanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $penghasilan = Penghasilan::all();
            return view('pelengkap.penghasilan.index', compact('penghasilan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('pelengkap.penghasilan.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_penghasilan' => 'required',
            ], [
                'nama_penghasilan.required' => 'Nama penghasilan wajib diisi.',
            ]);
            Penghasilan::create($validated);
            return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil disimpan');        
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $penghasilan = Penghasilan::findOrFail($id);
            return view('pelengkap.penghasilan.edit', compact('penghasilan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_penghasilan' => 'required',
            ], [
                'nama_penghasilan.required' => 'Nama penghasilan wajib diisi.',
            ]);
            $penghasilan = Penghasilan::findOrFail($id);
            $penghasilan->update($validated);
            return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $penghasilan = Penghasilan::findOrFail($id);
            $penghasilan->delete();
            return redirect()->route('penghasilan.index')->with('success', 'penghasilan berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
