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
    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
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
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
