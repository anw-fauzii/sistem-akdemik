<?php

namespace App\Http\Controllers;

use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class GuruController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $guru = Guru::all();
            return view('data_master.guru.index', compact('guru'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.guru.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
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

            DB::beginTransaction();

            try {
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['nipy'],
                    'password' => Hash::make('pass1234'),
                ]);

                $guru = new Guru($validated); 
                $guru->nipy = $validated['nipy'];
                $guru->save();
                $user->assignRole('guru_tk');
        
                DB::commit(); 
        
                return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
            } catch (\Exception $e) {
                DB::rollBack(); 
        
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $guru = Guru::findOrFail($id);
            return view('data_master.guru.edit', compact('guru'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nama_lengkap' => 'required',
                'gelar' => 'required',
                'jabatan' => 'required',
                'nipy' => 'required|unique:guru,nipy,' . $id . ',nipy',
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

            DB::beginTransaction();

            try {
                $user = User::where('email', $id)->first();
                if (!$user) {
                    throw new \Exception("User dengan email NIPY $id tidak ditemukan.");
                }
        
                $user->update([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['nipy'],
                ]);
        
                $guru = Guru::where('nipy', $user->email)->first();
                if (!$guru) {
                    throw new \Exception("Guru dengan NIPY $id tidak ditemukan.");
                }
        
                $guru->update($validated);
        
                DB::commit();
        
                return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
        
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $guru = Guru::findOrFail($id);
            if ($guru->user) { 
                $guru->user->delete();
            }
            $guru->delete();
        
            return redirect()->route('guru.index')->with('success', 'Guru dan akun User terkait berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function import(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $request->validate([
                'file_import' => 'required|mimes:xlsx,csv,xls',
            ]);

            try {
                Excel::import(new GuruImport, $request->file('file_import'));
                return back()->with('success', 'Peserta didik berhasil diimport');
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi error: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function format()
    {
        if (user()?->hasRole('admin')) {
            $file = public_path() . "/format_excel/format_import_guru.xlsx";
            $headers = array(
                'Content-Type: application/xlsx',
            );
            return Response::download($file, 'format_import_guru ' . date('Y-m-d H_i_s') . '.xlsx', $headers);
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
