<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        return view('data_master.siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('data_master.siswa.create');
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
            'nuptk' => 'nullable|unique:siswa,nuptk',
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
            $siswa = Siswa::findOrFail($id);
            $siswa->update($validated);

            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate');
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
        $siswa = Siswa::findOrFail($id);
        return view('data_master.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'gelar' => 'required',
            'jabatan' => 'required',
            'nipy' => 'required|unique:siswa,nipy,'. $id, 
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nuptk' => 'nullable|unique:siswa,nuptk',
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
            $siswa = Siswa::findOrFail($id);
            $siswa->update($validated);

            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        if ($siswa->user) { 
            $siswa->user->delete();
        }
        $siswa->delete();
    
        return redirect()->route('siswa.index')->with('success', 'Siswa dan akun User terkait berhasil dihapus');
    }

}