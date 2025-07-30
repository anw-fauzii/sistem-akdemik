<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Kesehatan;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
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
            $tahunAjaran = TahunAjaran::latest()->first();
            $kelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                    $query->where('tahun_ajaran_id', $tahunAjaran->id);
                })
                ->first();
            $pengumuman = Pengumuman::orderBy('id', 'desc')->take(3)->get();
            $agenda = Agenda::whereUnit($kelas->kelas->jenjang)->get()->map(function ($agenda) {
                $color = $agenda->unit === 'SD' ? '#007bff' : '#f39c12';
                return [
                    'title' => $agenda->unit . ': ' . $agenda->kegiatan,
                    'start' => $agenda->tanggal,
                    'color' => $color,
                ];
            });
            $riwayatKesehatan = Kesehatan::where('anggota_kelas_id', $kelas->id)
                ->with('bulanSpp')
                ->orderBy('bulan_spp_id')
                ->get();
            
            $bulanLabels = $riwayatKesehatan->map(fn($k) => $k->bulanSpp->nama_bulan);
            $tbData = $riwayatKesehatan->pluck('tb')->map(function ($val) {
                return is_numeric($val) ? (float) $val : 0;
            })->values()->all();

            $bbData = $riwayatKesehatan->pluck('bb')->map(function ($val) {
                return is_numeric($val) ? (float) $val : 0;
            })->values()->all();


            return view('dashboard.siswa', compact('agenda','pengumuman','bulanLabels', 'tbData', 'bbData'));
        }elseif (user()?->hasAnyRole(['guru_sd','guru_tk'])) {
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
            $tahunAjaran=TahunAjaran::latest()->first();
            $daftarBulan = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                ->where('guru_nipy', Auth::user()->email)
                ->first();

            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->pluck('id');

            $presensiAll = Presensi::whereIn('anggota_kelas_id', $anggotaKelas)->get();

            $dataChart = [];

            foreach ($daftarBulan as $bulan) {
                $tanggalAwal = Carbon::parse($bulan->bulan_angka)->startOfMonth();
                $tanggalAkhir = Carbon::parse($bulan->bulan_angka)->endOfMonth();

                $presensiBulan = $presensiAll->filter(function ($p) use ($tanggalAwal, $tanggalAkhir) {
                    return Carbon::parse($p->tanggal)->between($tanggalAwal, $tanggalAkhir);
                });
                $hariEfektif = $presensiBulan->count();

                $dataTepatWaktu = $presensiBulan->where('terlambat',FALSE)->count();

                $dataHadir = $presensiBulan->where('status','hadir')->count();
                $totalTepatWaktu = $hariEfektif > 0
                ? round(($dataTepatWaktu / $hariEfektif) * 100, 1)
                : 0;
                $totalHadir = $hariEfektif > 0
                ? round(($dataHadir / $hariEfektif) * 100, 1)
                : 0;

                $dataChart[] = [
                    'name' => Carbon::parse($bulan->bulan_angka)->translatedFormat('F Y'),
                    'tepat_waktu' => $totalTepatWaktu,
                    'hadir' => $totalHadir,
                    'absen' => round(100 - $totalHadir, 1),
                    'terlambat' => round(100 - $totalTepatWaktu, 1),
                ];
            }
            return view('dashboard.guru', compact('agenda','pengumuman','dataChart'));
        }  else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
