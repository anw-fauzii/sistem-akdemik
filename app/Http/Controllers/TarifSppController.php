<?php

namespace App\Http\Controllers;

use App\Models\TarifSpp;
use Illuminate\Http\Request;

class TarifSppController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tarif_spp = TarifSpp::all();
            return view('data_master.tarif_spp.index', compact('tarif_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.tarif_spp.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'unit' => 'required',
                'tahun_masuk' => 'required',
                'spp' => 'required',
                'biaya_makan' => 'required',
                'snack' => 'required',
            ], [
                'unit.required' => 'Nama tarif_spp wajib diisi.',
                'tahun_masuk.required' => 'Tanggal tarif wajib diisi.',
                'spp.required' => 'Jumlah spp harus berupa angka.', 
                'biaya_makan.required' => 'Jumlah spp harus berupa angka.', 
                'snack.required' => 'Jumlah spp harus berupa angka.', 
            ]);
            $validated['spp'] = (int) str_replace('.', '', $request->spp);
            $validated['biaya_makan'] = (int) str_replace('.', '', $request->biaya_makan);
            $validated['snack'] = (int) str_replace('.', '', $request->snack);
            TarifSpp::create($validated);   
            return redirect()->route('tarif-spp.index')->with('success', 'tarif_spp berhasil disimpan'); 
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }       
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $tarif_spp = TarifSpp::findOrFail($id);
            return view('data_master.tarif_spp.edit', compact('tarif_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'unit' => 'required',
                'tahun_masuk' => 'required',
                'spp' => 'required',
                'biaya_makan' => 'required',
                'snack' => 'required',
            ], [
                'unit.required' => 'Nama tarif_spp wajib diisi.',
                'tahun_masuk.required' => 'Tanggal tarif wajib diisi.',
                'spp.required' => 'Jumlah spp harus berupa angka.', 
                'biaya_makan.required' => 'Jumlah spp harus berupa angka.', 
                'snack.required' => 'Jumlah spp harus berupa angka.', 
            ]);
            $tarif_spp = TarifSpp::findOrFail($id);
            $validated['spp'] = (int) str_replace('.', '', $request->spp);
            $validated['biaya_makan'] = (int) str_replace('.', '', $request->biaya_makan);
            $validated['snack'] = (int) str_replace('.', '', $request->snack);
            $tarif_spp->update($validated);
            
            return redirect()->route('tarif-spp.index')->with('success', 'tarif_spp berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $tarif_spp = TarifSpp::findOrFail($id);
            $tarif_spp->delete();
            return redirect()->route('tarif-spp.index')->with('success', 'tarif_spp berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}