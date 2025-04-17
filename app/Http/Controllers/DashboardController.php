<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
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
            return view('dashboard', compact('siswa_sd','siswa_tk','kelas','guru', 'agenda'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
