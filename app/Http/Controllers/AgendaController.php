<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $agenda = Agenda::all();
            return view('informasi.agenda.index', compact('agenda'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('informasi.agenda.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'kegiatan' => 'required',
                'tanggal' => 'required',
                'unit' => 'required',
            ], [
                'kegiatan.required' => 'Nama agenda wajib diisi.',
                'tanggal.required' => 'Tanggal bulan wajib diisi.',
                'unit.required' => 'Jumlah unit harus berupa angka.', 
            ]);
            
            $validated['tahun_ajaran_id'] = $tahun->id;
            Agenda::create($validated);
            return redirect()->route('agenda.index')->with('success', 'agenda berhasil disimpan');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }    
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $agenda = Agenda::findOrFail($id);
            return view('informasi.agenda.edit', compact('agenda'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'kegiatan' => 'required',
                'tanggal' => 'required',
                'unit' => 'required',
            ], [
                'kegiatan.required' => 'Nama agenda wajib diisi.',
                'tanggal.required' => 'Tanggal bulan wajib diisi.',
                'unit.required' => 'Jumlah unit harus berupa angka.', 
            ]);
            $agenda = Agenda::findOrFail($id);
            $agenda->update($validated);
            return redirect()->route('agenda.index')->with('success', 'agenda berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $agenda = Agenda::findOrFail($id);
            $agenda->delete();
            return redirect()->route('agenda.index')->with('success', 'agenda berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
