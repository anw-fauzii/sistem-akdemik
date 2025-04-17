<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            if($tahun_ajaran){
                $data_kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)->orderBy('id', 'ASC')->get();
                return view('data_master.kelas.index', compact('data_kelas', 'tahun_ajaran'));
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
            $guru = Guru::whereStatus(true)->get();
            return view('data_master.kelas.create', compact('guru'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $validated = $request->validate([
                'guru_nipy' => 'required',
                'pendamping_nipy' => 'required',
                'tingkatan_kelas' => 'required',
                'nama_kelas' => 'required',
                'romawi' => 'required',
                'jenjang' => 'required',
                'spp' => 'required|numeric',
                'biaya_makan' => 'required|numeric',
            ], [
                'guru_nipy.required' => 'Jenjang sekolah wajib diisi.',
                'jenjang.required' => 'Wali kelas wajib diisi.',
                'pendamping_nipy.required' => 'Guru pendamping wajib diisi.',
                'tingkatan_kelas.required' => 'Tingkatan kelas wajib diisi.',
                'nama_kelas.required' => 'Nama kelas wajib diisi.',
                'romawi.required' => 'Romawi wajib diisi.',
                'spp.required' => 'SPP wajib diisi.',
                'spp.numeric' => 'SPP harus berupa angka.',
                'biaya_makan.required' => 'Biaya Makan wajib diisi.',
                'biaya_makan.numeric' => 'Biaya Makan harus berupa angka.',
            ]);
            if (!$tahun_ajaran) {
                return back()->withErrors(['tahun_ajaran_id' => 'Tahun ajaran tidak ditemukan.']);
            }
            $validated['tahun_ajaran_id'] = $tahun_ajaran->id;
            Kelas::create($validated);      
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil disimpan'); 
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }  
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $kelas = Kelas::findOrFail($id);
            $anggota_kelas = AnggotaKelas::whereKelasId($id)->get();
            $siswa_belum_masuk_kelas = Siswa::whereKelasId(NULL)->get();
            foreach ($siswa_belum_masuk_kelas as $belum_masuk_kelas) {
                $kelas_sebelumnya = AnggotaKelas::where('siswa_nis', $belum_masuk_kelas->id)->orderBy('id', 'DESC')->first();
                if (is_null($kelas_sebelumnya)) {
                    $belum_masuk_kelas->kelas_sebelumnya = null;
                } else {
                    $belum_masuk_kelas->kelas_sebelumnya = $kelas_sebelumnya->kelas->nama_kelas;
                }
            }
            return view('data_master.kelas.show', compact('kelas','anggota_kelas','siswa_belum_masuk_kelas'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $kelas = Kelas::findOrFail($id);
            $guru = Guru::whereStatus(true)->get();
            return view('data_master.kelas.edit', compact('guru','kelas'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'guru_nipy' => 'required',
                'pendamping_nipy' => 'required',
                'tingkatan_kelas' => 'required',
                'nama_kelas' => 'required',
                'romawi' => 'required',
                'jenjang' => 'required',
                'spp' => 'required|numeric',
                'biaya_makan' => 'required|numeric',
            ], [
                'guru_nipy.required' => 'Wali kelas wajib diisi.',
                'jenjang.required' => 'Wali kelas wajib diisi.',
                'pendamping_nipy.required' => 'Guru pendamping wajib diisi.',
                'tingkatan_kelas.required' => 'Tingkatan kelas wajib diisi.',
                'nama_kelas.required' => 'Nama kelas wajib diisi.',
                'romawi.required' => 'Romawi wajib diisi.',
                'spp.required' => 'SPP wajib diisi.',
                'spp.numeric' => 'SPP harus berupa angka.',
                'biaya_makan.required' => 'Biaya Makan wajib diisi.',
                'biaya_makan.numeric' => 'Biaya Makan harus berupa angka.',
            ]);
            $kelas = Kelas::findOrFail($id);
            $kelas->update($validated); 
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dipudate');   
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $kelas = Kelas::findOrFail($id);
            $kelas->delete(); 
            return redirect()->route('kelas.index')->with('success', 'Kelas terkait berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
