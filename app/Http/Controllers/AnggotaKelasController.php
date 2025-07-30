<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                for ($count = 0; $count < count($siswa_nis); $count++) {
                    $data = array(
                        'siswa_nis' => $siswa_nis[$count],
                        'kelas_id'  => $request->kelas_id,
                        'pendaftaran'  => $request->pendaftaran,
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

    public function index(){
        if(user()->hasAnyRole(['guru_sd','guru_tk'])){
            $tahunAjaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                ->where(function ($query) {
                    $query->where('guru_nipy', Auth::user()->email)
                        ->orWhere('pendamping_nipy', Auth::user()->email);
                })->firstOrFail();
            if($kelas){
                $anggotaKelas = AnggotaKelas::with(['siswa.ekstrakurikuler', 'siswa.guru'])
                    ->where('kelas_id', $kelas->id)
                    ->get();
                return view('anggota_kelas.index', compact('kelas','anggotaKelas'));
            }
        }
    }
}
