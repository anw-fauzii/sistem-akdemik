<?php

namespace App\Http\Controllers;

use App\Models\AnggotaJemputan;
use App\Models\AnggotaKelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaJemputanController extends Controller
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
                    Siswa::where('nis', $anggota->siswa_nis)->update(['jemputan_id' => $request->input('jemputan_id')]);
                }
                for ($count = 0; $count < count($siswa_nis); $count++) {
                    $data = array(
                        'anggota_kelas_id' => $siswa_nis[$count],
                        'jemputan_id'  => $request->jemputan_id,
                        'keterangan' => $request->keterangan,
                        'created_at'  => Carbon::now(),
                        'updated_at'  => Carbon::now(),
                    );
                    $insert_data[] = $data;
                }
                AnggotaJemputan::insert($insert_data);
                return back()->with('success', 'Anggota jemputan berhasil ditambahkan');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            try {
                $anggota = AnggotaJemputan::findOrFail($id);
                $anggotaKelas = $anggota->anggotaKelas;
                $siswa = Siswa::findOrFail($anggotaKelas->siswa_nis);
                $anggota->delete();
                $siswa->update([
                    'jemputan_id' => null,
                ]);

                return back()->with('success', 'Anggota jemputan berhasil dihapus');
            } catch (\Exception $e) {
                return back()->with('error', 'Anggota jemputan tidak dapat dihapus');
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}