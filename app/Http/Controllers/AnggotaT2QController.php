<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\AnggotaT2Q;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaT2QController extends Controller
{

    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            if($tahun_ajaran){
                $data_guru = Guru::withCount('anggotaT2q')->whereJabatan('T2Q')->whereStatus(TRUE)->get();
                return view('data_master.t2q.index', compact('data_guru', 'tahun_ajaran'));
            }else{
                return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validator = Validator::make($request->all(), [
                'siswa_nis' => 'required',
                'tingkat' => 'required',
            ]);
            if ($validator->fails()) {
                return back()->with('toast_warning', 'Tidak ada siswa yang dipilih');
            } else {
                $siswa_nis = $request->input('siswa_nis');
                foreach ($siswa_nis as $id){
                    $anggota = AnggotaKelas::find($id);
                    Siswa::where('nis', $anggota->siswa_nis)->update(['guru_nipy' => $request->input('guru_nipy')]);
                }
                for ($count = 0; $count < count($siswa_nis); $count++) {
                    $data = array(
                        'anggota_kelas_id' => $siswa_nis[$count],
                        'guru_nipy'  => $request->guru_nipy,
                        'tingkat' => $request->tingkat,
                        'created_at'  => Carbon::now(),
                        'updated_at'  => Carbon::now(),
                    );
                    $insert_data[] = $data;
                }
                AnggotaT2Q::insert($insert_data);
                return back()->with('success', 'Anggota T2Q berhasil ditambahkan');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $guru = Guru::findorfail($id);
            $anggota_t2q = AnggotaT2Q::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])->whereGuruNipy($guru->nipy)
                ->whereHas('anggotaKelas.kelas', function ($query) use ($tahun_ajaran) {
                $query->where('tahun_ajaran_id', $tahun_ajaran->id);
            })
            ->get();
            $kelas = Kelas::whereTahunAjaranId($tahun_ajaran->id)->pluck('id');
            $siswa_belum_masuk_t2q = AnggotaKelas::with([
                    'siswa:nis,nama_lengkap',
                    'kelas:id,nama_kelas'
                ])
                ->whereIn('kelas_id', $kelas)
                ->whereDoesntHave('anggotaT2q')
                ->get()
                ->map(function ($anggota) {
                    return (object) [
                        'id' => $anggota->id,
                        'nis' => $anggota->siswa->nis ?? '-',
                        'siswa_nama' => $anggota->siswa->nama_lengkap ?? '-',
                        'kelas' => $anggota->kelas->nama_kelas ?? '-',
                    ];
                });
            return view('data_master.t2q.show', compact('guru', 'anggota_t2q', 'siswa_belum_masuk_t2q'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnggotaT2Q $anggotaT2Q)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnggotaT2Q $anggotaT2Q)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnggotaT2Q $anggotaT2Q)
    {
        //
    }
}
