<?php

namespace App\Http\Controllers;

use App\Models\AnggotaJemputan;
use App\Models\AnggotaKelas;
use App\Models\Jemputan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class JemputanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            if($tahun_ajaran){
                $jemputan = jemputan::whereTahunAjaranId($tahun_ajaran->id)->orderBy('id', 'ASC')->get();
                foreach ($jemputan as $item) {
                    $jumlah_anggota = AnggotaJemputan::whereJemputanId($item->id)->count();
                    $item->jumlah_anggota = $jumlah_anggota;
                }
                return view('data_master.jemputan.index', compact('jemputan'));
            }else{
                return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            return view('data_master.jemputan.create');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'driver' => 'required',
                'harga_pp' => 'required',
                'harga_setengah' => 'required',
            ], [
                'driver.required' => 'Nama Driver wajib diisi.',
                'harga_pp.required' => 'Harga Pulang Pergi wajib diisi.',
                'harga_setengah.required' => 'Harga Setengah wajib diisi.',
            ]);
            $validated['tahun_ajaran_id'] = $tahun->id;
            Jemputan::create($validated);   
            return redirect()->route('jemputan.index')->with('success', 'jemputan berhasil disimpan');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }    
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $jemputan = Jemputan::findorfail($id);
            $anggota_jemputan = AnggotaJemputan::whereJemputanId($id)->get();
            $kelas = Kelas::whereTahunAjaranId($tahun_ajaran->id)->pluck('id');
            $siswa_belum_masuk_jemputan = AnggotaKelas::with(['siswa', 'kelas'])
                ->whereIn('kelas_id', $kelas)
                ->whereDoesntHave('anggota_jemputan')
                ->get()
                ->map(function ($anggota) {
                    $anggota->kelas = $anggota->kelas->nama_kelas ?? null;
                    $anggota->siswa_nama = $anggota->siswa->nama_lengkap ?? '-';
                    return $anggota;
                });
            return view('data_master.jemputan.show', compact('jemputan','anggota_jemputan','siswa_belum_masuk_jemputan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $jemputan = Jemputan::findOrFail($id);
            return view('data_master.jemputan.edit', compact('jemputan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'driver' => 'required',
                'harga_pp' => 'required',
                'harga_setengah' => 'required',
            ], [
                'driver.required' => 'Nama Driver wajib diisi.',
                'harga_pp.required' => 'Harga Pulang Pergi wajib diisi.',
                'harga_setengah.required' => 'Harga Setengah wajib diisi.',
            ]);
            $jemputan = Jemputan::findOrFail($id);
            $jemputan->update($validated);
            return redirect()->route('jemputan.index')->with('success', 'jemputan berhasil diupdate');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $jemputan = Jemputan::findOrFail($id);
            $jemputan->delete();
            return redirect()->route('jemputan.index')->with('success', 'jemputan berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
