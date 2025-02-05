<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        return view('data_master.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('data_master.guru.create');
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'gelar' => 'required',
            'jabatan' => 'required',
            'nipy' => 'required|unique:users,email', 
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nuptk' => 'nullable|unique:guru,nuptk',
            'alamat' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'gelar.required' => 'Gelar wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'nipy.required' => 'NIPY wajib diisi.',
            'nipy.unique' => 'NIPY sudah digunakan.',
            'telepon.required' => 'Telepon wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'nuptk.required' => 'NUPTK sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        try {
            $guru = Guru::findOrFail($id);
            $guru->update($validated);

            return redirect()->route('guru.index')->with('success', 'Tahun ajaran berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('data_master.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'gelar' => 'required',
            'jabatan' => 'required',
            'nipy' => 'required|unique:guru,nipy,'. $id, 
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nuptk' => 'nullable|unique:guru,nuptk',
            'alamat' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'gelar.required' => 'Gelar wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'nipy.required' => 'NIPY wajib diisi.',
            'nipy.unique' => 'NIPY sudah digunakan.',
            'telepon.required' => 'Telepon wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'nuptk.required' => 'NUPTK sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
        ]);

        try {
            $guru = Guru::findOrFail($id);
            $guru->update($validated);

            return redirect()->route('guru.index')->with('success', 'Tahun ajaran berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        if ($guru->user) { 
            $guru->user->delete();
        }
        $guru->delete();
    
        return redirect()->route('guru.index')->with('success', 'Guru dan akun User terkait berhasil dihapus');
    }

}
