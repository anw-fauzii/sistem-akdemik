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
    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

}
