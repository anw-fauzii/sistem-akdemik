<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_nis' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('warning', 'Tidak ada siswa yang dipilih');
        } else {
            $siswa_nis = $request->input('siswa_nis');
            $tapel = TahunAjaran::latest()->first();
            for ($count = 0; $count < count($siswa_nis); $count++) {
                $data = array(
                    'siswa_nis' => $siswa_nis[$count],
                    'kelas_id'  => $request->kelas_id,
                    'pendaftaran'  => $request->pendaftaran,
                    'tahun_ajaran_id' => $tapel->id,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                );
                $insert_data[] = $data;
            }

            AnggotaKelas::insert($insert_data);
            Siswa::whereIn('nis', $siswa_nis)->update(['kelas_id' => $request->input('kelas_id')]);
            return back()->with('success', 'Anggota kelas berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AnggotaKelas $anggotaKelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnggotaKelas $anggotaKelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnggotaKelas $anggotaKelas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $anggota_kelas = AnggotaKelas::findorfail($id);
            $siswa = Siswa::findorfail($anggota_kelas->siswa_nis);

            $update_kelas_id = [
                'kelas_id' => null,
            ];
            $anggota_kelas->delete();
            $siswa->update($update_kelas_id);
            return back()->with('success', 'Anggota kelas berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Anggota kelas tidak dapat dihapus');
        }
    }
}
