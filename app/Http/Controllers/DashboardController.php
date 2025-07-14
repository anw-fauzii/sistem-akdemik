<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AnggotaKelas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (user()?->roles->isEmpty()) {
            auth()->logout(); 
            return redirect()->route('login')->with('error', 'Anda belum memiliki role akses!');
        }
        if (user()?->hasRole('admin')) {
            $agenda = Agenda::all()->map(function ($agenda) {
                $color = $agenda->unit === 'SD' ? '#007bff' : '#f39c12';

                return [
                    'title' => $agenda->unit . ': ' . $agenda->kegiatan,
                    'start' => $agenda->tanggal,
                    'color' => $color,
                ];
            });
            $tahun_ajaran = TahunAjaran::latest()->first();
            $siswa_tk = Siswa::whereHas('kelas', function($query) {
                $query->where('jenjang', 'PG TK');
            })->whereStatus('1')->count();
        
            $siswa_sd = Siswa::whereHas('kelas', function($query) {
                $query->where('jenjang', 'SD');
            })->whereStatus('1')->count();
            $kelas = Kelas::whereTahunAjaranId($tahun_ajaran->id)->count();
            $guru = Guru::whereStatus('1')->count();
            return view('dashboard.admin', compact('siswa_sd','siswa_tk','kelas','guru', 'agenda'));
        } 
        elseif (user()?->hasRole('siswa')) {
            $pengumuman = Pengumuman::orderBy('id', 'desc')->take(3)->get();
            $kelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)->firstOrFail();
            $agenda = Agenda::whereUnit($kelas->kelas->jenjang)->get()->map(function ($agenda) {
                $color = $agenda->unit === 'SD' ? '#007bff' : '#f39c12';
                return [
                    'title' => $agenda->unit . ': ' . $agenda->kegiatan,
                    'start' => $agenda->tanggal,
                    'color' => $color,
                ];
            });
            return view('dashboard.siswa', compact('agenda','pengumuman'));
        }elseif (user()?->hasRole('guru')) {
            $pengumuman = Pengumuman::orderBy('id', 'desc')->take(3)->get();
            $guru = Guru::findOrFail(Auth::user()->email);
            $agenda = Agenda::whereUnit($guru->unit)->get()->map(function ($agenda) {
                $color = $agenda->unit === 'SD' ? '#007bff' : '#f39c12';
                return [
                    'title' => $agenda->unit . ': ' . $agenda->kegiatan,
                    'start' => $agenda->tanggal,
                    'color' => $color,
                ];
            });
            return view('dashboard.siswa', compact('agenda','pengumuman'));
        }  else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
