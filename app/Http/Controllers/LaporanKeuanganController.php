<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\PembayaranTagihanTahunan;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
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
                foreach ($tagihan_list as $tagihan) {
                    $dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota->id)
                        ->where('tagihan_tahunan_id', $tagihan->id)
                        ->sum('jumlah_bayar');
    
                    $sisa = $tagihan->jumlah - $dibayar;
    
                    $total_tagihan_kelas += $tagihan->jumlah;
                    $total_dibayar_kelas += $dibayar;
                    $total_sisa_kelas += $sisa;
    
                    if ($sisa > 0) {
                        $jumlah_siswa_belum_lunas++;
                    }
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

}
