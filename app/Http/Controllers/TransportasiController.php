<?php

namespace App\Http\Controllers;

use App\Models\Transportasi;
use Illuminate\Http\Request;

class TransportasiController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $transportasi = Transportasi::all();
            return view('pelengkap.transportasi.index', compact('transportasi'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('pelengkap.transportasi.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_transportasi' => 'required',
            ], [
                'nama_transportasi.required' => 'Nama transportasi wajib diisi.',
            ]);
            Transportasi::create($validated);
            return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil disimpan');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }     
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $transportasi = Transportasi::findOrFail($id);
            return view('pelengkap.transportasi.edit', compact('transportasi'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_transportasi' => 'required',
            ], [
                'nama_transportasi.required' => 'Nama transportasi wajib diisi.',
            ]);
            $transportasi = Transportasi::findOrFail($id);
            $transportasi->update($validated);
            return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $transportasi = Transportasi::findOrFail($id);
            $transportasi->delete();
            return redirect()->route('transportasi.index')->with('success', 'Transportasi berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}