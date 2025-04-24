<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Presensi;
use App\Models\PresensiEkstrakurikuler;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function indexTagihanTahunan()
    {
        $tahun_ajaran = TahunAjaran::where('semester', '1')->get();
        $tahun_ajaran_sekarang = TahunAjaran::latest()->first();
        $kelas_list = Kelas::whereTahunAjaranId($tahun_ajaran_sekarang->id)->get();
    
        // Ringkasan total keseluruhan
        $total_tagihan_semua = 0;
        $total_dibayar_semua = 0;
        $total_sisa_semua = 0;
        $jumlah_siswa_belum_lunas = 0;
    
        $hasil = []; // untuk rekap per kelas
    
        foreach ($kelas_list as $kelas) {
            $total_tagihan_kelas = 0;
            $total_dibayar_kelas = 0;
            $total_sisa_kelas = 0;
    
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();
    
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
    
            // Tambahkan ke rekap per kelas
            $hasil[] = [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'total_tagihan' => $total_tagihan_kelas,
                'total_dibayar' => $total_dibayar_kelas,
                'total_sisa' => $total_sisa_kelas,
                'status' => $total_sisa_kelas == 0 ? 'Lunas' : 'Belum Lunas'
            ];
    
            // Tambahkan ke total keseluruhan
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
        $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();

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

        $laporan = $kelasList->map(function ($kelas) {
            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

            $totalTagihanKelas = 0;
            $totalBayarKelas = 0;

            foreach ($anggotaKelas as $anggota) {
                $bulanSpp = BulanSpp::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();

                foreach ($bulanSpp as $bulan) {
                    $tambahan = $bulan->tambahan ?? 0;
                    $bulanAngka = date('m', strtotime($bulan->bulan_angka));

                    // === POTONGAN MAKAN KARENA SAKIT ===
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

                    // === BIAYA EKSKUL ===
                    $biayaEkskul = $anggota->ekstrakurikuler->sum(function ($item) use ($bulanAngka) {
                        $hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                            ->where('status', 'hadir')
                            ->whereMonth('tanggal', $bulanAngka)
                            ->exists();

                        return $hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                    });

                    // === HITUNG TOTAL TAGIHAN BULAN INI ===
                    $tagihan = $kelas->spp + $biayaMakanFinal + $tambahan + $biayaEkskul;

                    // === CARI PEMBAYARAN BULAN INI ===
                    $pembayaran = PembayaranSpp::where('anggota_kelas_id', $anggota->id)
                        ->where('bulan_spp_id', $bulan->id)
                        ->first();

                    if ($pembayaran) {
                        $totalBayarKelas += $pembayaran->total_pembayaran;
                    }

                    $totalTagihanKelas += $tagihan;
                }
            }

            $totalBelumBayarKelas = $totalTagihanKelas - $totalBayarKelas;

            return [
                'id' => $kelas->id,
                'kelas' => $kelas->nama_kelas,
                'total_tagihan' => $totalTagihanKelas,
                'total_bayar' => $totalBayarKelas,
                'total_belum_bayar' => $totalBelumBayarKelas
            ];
        });

        return view('laporan.spp.index', compact('laporan'));
    }

    public function showTagihanSpp($id)
    {
        $kelas = Kelas::find($id);
        $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();

        $laporan_per_siswa = $anggotaKelas->map(function ($anggota) use ($kelas) {
            $bulanSpp = BulanSpp::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)->get();

            $totalTagihan = 0;
            $totalBayar = 0;

            foreach ($bulanSpp as $bulan) {
                $bulanAngka = date('m', strtotime($bulan->bulan_angka));
                $tambahan = $bulan->tambahan ?? 0;

                // Presensi sakit
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

                // Biaya ekstrakurikuler
                $biaya_ekskul = $anggota->ekstrakurikuler->sum(function ($item) use ($bulanAngka) {
                    $pernah_hadir = \App\Models\PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulanAngka)
                        ->exists();

                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });

                $tagihan = $kelas->spp + $total_biaya_makan + $biaya_ekskul + $tambahan;

                $pembayaran = PembayaranSpp::where('anggota_kelas_id', $anggota->id)
                    ->where('bulan_spp_id', $bulan->id)
                    ->first();

                if ($pembayaran) {
                    $totalBayar += $pembayaran->total_pembayaran;
                }

                $totalTagihan += $tagihan;
            }

            $totalBelumBayar = $totalTagihan - $totalBayar;

            return [
                'siswa' => $anggota->siswa_nis,
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $totalBayar,
                'total_belum_bayar' => $totalBelumBayar
            ];
        });

        return view('laporan.spp.show', compact('kelas','laporan_per_siswa'));
    }

}
