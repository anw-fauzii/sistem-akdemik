<?php

namespace App\Http\Controllers;

use App\Models\AnggotaJemputan;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranJemputan;
use App\Models\PembayaranSpp;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Presensi;
use App\Models\PresensiEkstrakurikuler;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    public function indexTagihanTahunan()
    {
        $tahun_ajaran = TahunAjaran::where('semester', '1')->get();
        $tahun_ajaran_sekarang = TahunAjaran::latest()->first();
        $kelas_list = Kelas::whereTahunAjaranId($tahun_ajaran_sekarang->id)->get();
    
        $total_tagihan_semua = 0;
        $total_dibayar_semua = 0;
        $total_sisa_semua = 0;
        $jumlah_siswa_belum_lunas = 0;
    
        $hasil = [];
    
        foreach ($kelas_list as $kelas) {
            $total_tagihan_kelas = 0;
            $total_dibayar_kelas = 0;
            $total_sisa_kelas = 0;
    
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                ->where('jenjang', $kelas->jenjang)
                ->where(function ($query) use ($kelas) {
                    $query->where('kelas', $kelas->tingkatan_kelas)
                        ->orWhereNull('kelas');
                })
                ->get();
    
            foreach ($kelas->anggotaKelas as $anggota) {
                $siswa_belum_lunas = false;
            
                foreach ($tagihan_list as $tagihan) {
                    $dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota->id)
                        ->where('tagihan_tahunan_id', $tagihan->id)
                        ->sum('jumlah_bayar');
            
                    $sisa = $tagihan->jumlah - $dibayar;
            
                    $total_tagihan_kelas += $tagihan->jumlah;
                    $total_dibayar_kelas += $dibayar;
                    $total_sisa_kelas += $sisa;
            
                    if ($sisa > 0) {
                        $siswa_belum_lunas = true;
                    }
                }
            
                if ($siswa_belum_lunas) {
                    $jumlah_siswa_belum_lunas++;
                }
            }            
    
            $hasil[] = [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'total_tagihan' => $total_tagihan_kelas,
                'total_dibayar' => $total_dibayar_kelas,
                'total_sisa' => $total_sisa_kelas,
                'status' => $total_sisa_kelas == 0 ? 'Lunas' : 'Belum Lunas'
            ];
    
            $total_tagihan_semua += $total_tagihan_kelas;
            $total_dibayar_semua += $total_dibayar_kelas;
            $total_sisa_semua += $total_sisa_kelas;
        }
    
        return view('laporan.tagihan_tahunan.index', compact(
            'kelas_list',
            'tahun_ajaran',
            'total_tagihan_semua',
            'total_dibayar_semua',
            'total_sisa_semua',
            'jumlah_siswa_belum_lunas',
            'hasil'
        ));
    }
    
    public function showTagihanTahunan($kelas_id)
    {
        $kelas = Kelas::with(['anggotaKelas.siswa'])->findOrFail($kelas_id);
        $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                ->where('jenjang', $kelas->jenjang)
                ->where(function ($query) use ($kelas) {
                    $query->where('kelas', $kelas->tingkatan_kelas)
                        ->orWhereNull('kelas');
                })
                ->get();
    

        $hasil = [];
        $total_tagihan = 0;
        $total_dibayar = 0;
        $total_sisa = 0;
        $jumlah_siswa_belum_lunas = 0;

        foreach ($kelas->anggotaKelas as $anggota) {
            $siswa = $anggota->siswa;

            $total_tagihan_siswa = 0;
            $total_dibayar_siswa = 0;
            $sisa_siswa = 0;

            foreach ($tagihan_list as $tagihan) {
                $dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota->id)
                    ->where('tagihan_tahunan_id', $tagihan->id)
                    ->sum('jumlah_bayar');

                $sisa = $tagihan->jumlah - $dibayar;

                $total_tagihan_siswa += $tagihan->jumlah;
                $total_dibayar_siswa += $dibayar;
                $sisa_siswa += $sisa;
            }

            $hasil[] = [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama_lengkap,
                'total_tagihan' => $total_tagihan_siswa,
                'total_dibayar' => $total_dibayar_siswa,
                'sisa_tagihan' => $sisa_siswa,
                'status' => $sisa_siswa == 0 ? 'Lunas' : 'Belum Lunas'
            ];

            $total_tagihan += $total_tagihan_siswa;
            $total_dibayar += $total_dibayar_siswa;
            $total_sisa += $sisa_siswa;

            if ($sisa_siswa > 0) {
                $jumlah_siswa_belum_lunas++;
            }
        }

        return view('laporan.tagihan_tahunan.show', compact(
            'kelas',
            'hasil',
            'total_tagihan',
            'total_dibayar',
            'total_sisa',
            'jumlah_siswa_belum_lunas'
        ));
    }

    public function indexTagihanSpp()
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $kelasList = Kelas::whereTahunAjaranId($tahun_ajaran->id)->get();

        $total_rekap_tagihan = 0;
        $total_rekap_bayar = 0;
        $total_rekap_belum_bayar = 0;
        $jumlah_siswa_belum_lunas = 0;

        $laporan = $kelasList->map(function ($kelas) use (&$total_rekap_tagihan, &$total_rekap_bayar, &$total_rekap_belum_bayar, &$jumlah_siswa_belum_lunas) {
            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

            $totalTagihanKelas = 0;
            $totalBayarKelas = 0;
            $siswaBelumLunasKelas = 0;

            foreach ($anggotaKelas as $anggota) {
                $bulanSpp = BulanSpp::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();

                foreach ($bulanSpp as $bulan) {
                    $tambahan = $bulan->tambahan ?? 0;
                    $bulanAngka = date('m', strtotime($bulan->bulan_angka));

                    $absenSakit = Presensi::where('anggota_kelas_id', $anggota->id)
                        ->where('status', 'sakit')
                        ->whereMonth('tanggal', $bulanAngka)
                        ->pluck('tanggal')
                        ->map(fn($tanggal) => Carbon::parse($tanggal));

                    $potongan = 0;
                    if ($absenSakit->count() >= 7) {
                        $sorted = $absenSakit->sort()->values();
                        $streak = 1;
                        for ($i = 1; $i < $sorted->count(); $i++) {
                            $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                            if ($diff == 1) {
                                $streak++;
                                if ($streak >= 7) {
                                    $potongan = 0.25 * $kelas->biaya_makan;
                                    break;
                                }
                            } else {
                                $streak = 1;
                            }
                        }
                    }

                    $biayaMakanFinal = $kelas->biaya_makan - $potongan;

                    $biayaEkskul = $anggota->ekstrakurikuler->sum(function ($item) use ($bulanAngka) {
                        $hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                            ->where('status', 'hadir')
                            ->whereMonth('tanggal', $bulanAngka)
                            ->exists();

                        return $hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                    });
                    $pembayaranJemputan = 0;

                    $anggotaJemputan = AnggotaJemputan::where('anggota_kelas_id', $anggota->id)
                        ->whereHas('jemputan', function ($q) use ($kelas) {
                            $q->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
                        })
                        ->first();

                    if ($anggotaJemputan) {
                        $bayarJemputan = PembayaranJemputan::where('anggota_jemputan_id', $anggotaJemputan->id)
                            ->where('bulan_spp_id', $bulan->id)
                            ->first();

                        if ($bayarJemputan) {
                            $pembayaranJemputan = $bayarJemputan->jumlah_bayar;
                        }
                    }

                    $tagihan = $kelas->spp + $biayaMakanFinal + $tambahan + $biayaEkskul + $pembayaranJemputan;

                    $pembayaran = PembayaranSpp::whereAnggotaKelasId($anggota->id)
                        ->whereBulanSppId($bulan->id)->whereKeterangan('LUNAS')
                        ->first();

                    if ($pembayaran) {
                        $totalBayarKelas += $pembayaran->total_pembayaran;
                    }

                    $totalTagihanKelas += $tagihan;
                }

                $totalBelumBayar = $totalTagihanKelas - $totalBayarKelas;
                if ($totalBelumBayar > 0) {
                    $siswaBelumLunasKelas++; 
                }
            }
            $totalBelumBayarKelas = $totalTagihanKelas - $totalBayarKelas;

            $total_rekap_tagihan += $totalTagihanKelas;
            $total_rekap_bayar += $totalBayarKelas;
            $total_rekap_belum_bayar += $totalBelumBayarKelas;
            $jumlah_siswa_belum_lunas += $siswaBelumLunasKelas;

            return [
                'id' => $kelas->id,
                'kelas' => $kelas->nama_kelas,
                'total_tagihan' => $totalTagihanKelas,
                'total_bayar' => $totalBayarKelas,
                'total_belum_bayar' => $totalBelumBayarKelas
            ];
        });

        return view('laporan.spp.index', compact(
            'laporan', 
            'total_rekap_tagihan', 
            'total_rekap_bayar', 
            'total_rekap_belum_bayar', 
            'jumlah_siswa_belum_lunas'
        ));
    }



    public function showTagihanSpp($id)
    {
        $kelas = Kelas::find($id);
        $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        $total_rekap_tagihan = 0;
        $total_rekap_bayar = 0;
        $total_rekap_belum_bayar = 0;
        $jumlah_siswa_belum_lunas = 0;
        
        $laporan_per_siswa = $anggotaKelas->map(function ($anggota) use ($kelas, &$total_rekap_tagihan, &$total_rekap_bayar, &$total_rekap_belum_bayar, &$jumlah_siswa_belum_lunas) {
            $bulanSpp = BulanSpp::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();

            $totalTagihan = 0;
            $totalBayar = 0;

            foreach ($bulanSpp as $bulan) {
                $bulanAngka = date('m', strtotime($bulan->bulan_angka));
                $tambahan = $bulan->tambahan ?? 0;

                $absen_sakit = Presensi::where('anggota_kelas_id', $anggota->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulanAngka)
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
                                $potongan = 0.25 * $kelas->biaya_makan;
                                break;
                            }
                        } else {
                            $streak = 1;
                        }
                    }
                }

                $total_biaya_makan = $kelas->biaya_makan - $potongan;

                $biaya_ekskul = $anggota->ekstrakurikuler->sum(function ($item) use ($bulanAngka) {
                    $pernah_hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulanAngka)
                        ->exists();

                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });
                $pembayaranJemputan = 0;

                $anggotaJemputan = AnggotaJemputan::where('anggota_kelas_id', $anggota->id)
                    ->whereHas('jemputan', function ($q) use ($kelas) {
                        $q->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
                    })
                    ->first();

                if ($anggotaJemputan) {
                    $bayarJemputan = PembayaranJemputan::where('anggota_jemputan_id', $anggotaJemputan->id)
                        ->where('bulan_spp_id', $bulan->id)
                        ->first();

                    if ($bayarJemputan) {
                        $pembayaranJemputan = $bayarJemputan->jumlah_bayar;
                    }
                }
                $tagihan = $kelas->spp + $total_biaya_makan + $biaya_ekskul + $tambahan + $pembayaranJemputan;

                $pembayaran = PembayaranSpp::whereAnggotaKelasId($anggota->id)
                        ->whereBulanSppId($bulan->id)->whereKeterangan('LUNAS')
                        ->first();

                if ($pembayaran) {
                    $totalBayar += $pembayaran->total_pembayaran;
                }

                $totalTagihan += $tagihan;
                
            }

            $totalBelumBayar = $totalTagihan - $totalBayar;

            $total_rekap_tagihan += $totalTagihan;
            $total_rekap_bayar += $totalBayar;
            $total_rekap_belum_bayar += $totalBelumBayar;
            if ($totalBelumBayar > 0) {
                $jumlah_siswa_belum_lunas++;
            }
            
            return [
                'siswa' => $anggota->siswa_nis,
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $totalBayar,
                'total_belum_bayar' => $totalBelumBayar
            ];
        });
        return view('laporan.spp.show', compact('kelas','laporan_per_siswa','total_rekap_tagihan','total_rekap_bayar','total_rekap_belum_bayar','jumlah_siswa_belum_lunas'));
    }

}
