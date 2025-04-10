<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Models\AnggotaKelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaEkstrakurikulerController extends Controller
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
            return back()->with('toast_warning', 'Tidak ada siswa yang dipilih');
        } else {
            $siswa_nis = $request->input('siswa_nis');
            foreach ($siswa_nis as $id){
                $anggota = AnggotaKelas::find($id);
                Siswa::where('nis', $anggota->siswa_nis)->update(['ekstrakurikuler_id' => $request->input('ekstrakurikuler_id')]);
            }
            for ($count = 0; $count < count($siswa_nis); $count++) {
                $data = array(
                    'anggota_kelas_id' => $siswa_nis[$count],
                    'ekstrakurikuler_id'  => $request->ekstrakurikuler_id,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                );
                $insert_data[] = $data;
            }
            AnggotaEkstrakurikuler::insert($insert_data);
            return back()->with('success', 'Anggota ekstrakurikuler berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AnggotaEkstrakurikuler $anggotaEkstrakurikuler)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnggotaEkstrakurikuler $anggotaEkstrakurikuler)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnggotaEkstrakurikuler $anggotaEkstrakurikuler)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $anggota = AnggotaEkstrakurikuler::findOrFail($id); // model anggota ekskul
            $anggotaKelas = $anggota->anggotaKelas; // relasi ke anggota_kelas
            $siswa = Siswa::findOrFail($anggotaKelas->siswa_nis); // dapatkan siswa

            // hapus relasi ke ekstrakurikuler
            $anggota->delete();

            // update siswa agar kolom ekstrakurikuler_id menjadi null
            $siswa->update([
                'ekstrakurikuler_id' => null,
            ]);

            return back()->with('success', 'Anggota ekstrakurikuler berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Anggota ekstrakurikuler tidak dapat dihapus');
        }
    }

}
