<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggotaKelas = AnggotaKelas::whereTahunAjaranId($tahunAjaran->id)
                        ->whereSiswaNis(Auth::user()->email)
                        ->firstOrFail();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
        
            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $kelas = Kelas::find($anggotaKelas->kelas_id);
            $spp = $kelas ? $kelas->spp : 0;
            $biaya_makan = $kelas ? $kelas->biaya_makan : 0;
        
            $tagihan_spp = BulanSpp::leftJoin('pembayaran_spp', function ($join) use ($anggotaKelas) {
                $join->on('bulan_spp.id', '=', 'pembayaran_spp.bulan_spp_id')
                    ->where('pembayaran_spp.anggota_kelas_id', '=', $anggotaKelas->id);
            })
            ->select(
                'bulan_spp.*',
                'pembayaran_spp.keterangan',
                'pembayaran_spp.id as pembayaran_id'
            )
            ->get()
            ->map(function ($tagihan) use ($anggotaKelas, $biaya_makan) {
                $bulan = date('m', strtotime($tagihan->bulan_angka));
                $absen_sakit = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulan)
                    ->orderBy('tanggal', 'asc')
                    ->pluck('tanggal')
                    ->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal));
        
                $potongan = 0;
                if ($absen_sakit->count() >= 7) {
                    $sorted = $absen_sakit->sort()->values();
                    $streak = 1;
                    for ($i = 1; $i < $sorted->count(); $i++) {
                        $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                        if ($diff == 1) {
                            $streak++;
                            if ($streak >= 7) {
                                $potongan = 0.25 * $biaya_makan;
                                break;
                            }
                        } else {
                            $streak = 1;
                        }
                    }
                }
        
                $total_biaya_makan = $biaya_makan - $potongan;
        
                $biaya_ekskul_bulan_ini = $anggotaKelas->ekstrakurikuler->sum(function ($item) use ($bulan) {
                    $pernah_hadir = \App\Models\PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulan)
                        ->exists();
        
                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });
        
                $tagihan->jumlah_absen = $absen_sakit->count();
                $tagihan->potongan_makan = $potongan;
                $tagihan->total_biaya_makan = $total_biaya_makan;
                $tagihan->biaya_ekskul = $biaya_ekskul_bulan_ini;
        
                return $tagihan;
            });
        
            $total_ekskul = $tagihan_spp->sum('biaya_ekskul');
            $tahun_selama_belajar = AnggotaKelas::whereSiswaNis(Auth::user()->email)->get();
            return view('pesertaDidik.keuangan.index', compact(
                'tahunAjaran',
                'siswa',
                'tagihan_spp',
                'spp',
                'biaya_makan',
                'total_ekskul',
                'tahun_selama_belajar'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            $anggotaKelas = AnggotaKelas::whereTahunAjaranId($tahunAjaran->id)
                        ->whereSiswaNis(Auth::user()->email)
                        ->firstOrFail();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
        
            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $kelas = Kelas::find($anggotaKelas->kelas_id);
            $spp = $kelas ? $kelas->spp : 0;
            $biaya_makan = $kelas ? $kelas->biaya_makan : 0;
        
            $tagihan_spp = BulanSpp::leftJoin('pembayaran_spp', function ($join) use ($anggotaKelas) {
                $join->on('bulan_spp.id', '=', 'pembayaran_spp.bulan_spp_id')
                    ->where('pembayaran_spp.anggota_kelas_id', '=', $anggotaKelas->id);
            })
            ->select(
                'bulan_spp.*',
                'pembayaran_spp.keterangan',
                'pembayaran_spp.id as pembayaran_id'
            )
            ->get()
            ->map(function ($tagihan) use ($anggotaKelas, $biaya_makan) {
                $bulan = date('m', strtotime($tagihan->bulan_angka));
                $absen_sakit = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulan)
                    ->orderBy('tanggal', 'asc')
                    ->pluck('tanggal')
                    ->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal));
        
                $potongan = 0;
                if ($absen_sakit->count() >= 7) {
                    $sorted = $absen_sakit->sort()->values();
                    $streak = 1;
                    for ($i = 1; $i < $sorted->count(); $i++) {
                        $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                        if ($diff == 1) {
                            $streak++;
                            if ($streak >= 7) {
                                $potongan = 0.25 * $biaya_makan;
                                break;
                            }
                        } else {
                            $streak = 1;
                        }
                    }
                }
        
                $total_biaya_makan = $biaya_makan - $potongan;
        
                $biaya_ekskul_bulan_ini = $anggotaKelas->ekstrakurikuler->sum(function ($item) use ($bulan) {
                    $pernah_hadir = \App\Models\PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulan)
                        ->exists();
        
                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });
        
                $tagihan->jumlah_absen = $absen_sakit->count();
                $tagihan->potongan_makan = $potongan;
                $tagihan->total_biaya_makan = $total_biaya_makan;
                $tagihan->biaya_ekskul = $biaya_ekskul_bulan_ini;
        
                return $tagihan;
            });
        
            $total_ekskul = $tagihan_spp->sum('biaya_ekskul');
            $tahun_selama_belajar = AnggotaKelas::whereSiswaNis(Auth::user()->email)->get();
            return view('pesertaDidik.keuangan.index', compact(
                'tahunAjaran',
                'siswa',
                'tagihan_spp',
                'spp',
                'biaya_makan',
                'total_ekskul',
                'tahun_selama_belajar'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
