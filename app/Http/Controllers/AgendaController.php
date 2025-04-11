<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agenda = Agenda::all();
        return view('data_master.agenda.index', compact('agenda'));
    }

    public function create()
    {
        return view('data_master.agenda.create');
    }

    public function store(Request $request)
    {
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
        
        $agenda = new Agenda($validated);
        $agenda->tahun_ajaran_id = $tahun->id;
        $agenda->save();
        return redirect()->route('agenda.index')->with('success', 'agenda berhasil disimpan');        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('data_master.agenda.edit', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();
        return redirect()->route('agenda.index')->with('success', 'agenda berhasil dihapus');
    }
}
