<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        if($tahun_ajaran){
            $data_kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)->orderBy('id', 'ASC')->get();
            foreach ($data_kelas as $kelas) {
                $jumlah_anggota = Siswa::where('kelas_id', $kelas->id)->count();
                $kelas->jumlah_anggota = $jumlah_anggota;
            }
            $data_guru = Guru::orderBy('nama_lengkap', 'ASC')->get();
            return view('data_master.kelas.index', compact('data_kelas', 'tahun_ajaran', 'data_guru'));
        }else{
            return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
        }
    }

    public function create()
    {
        $guru = Guru::whereStatus(false)->get();
        return view('data_master.kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Kelas $kelas)
    {
        //
    }

    public function edit(Kelas $kelas)
    {
        //
    }

    public function update(Request $request, Kelas $kelas)
    {
        //
    }

    public function destroy(Kelas $kelas)
    {
        //
    }
}
