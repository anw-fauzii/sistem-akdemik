<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\PembayaranTagihanTahunan;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function indexTagihanTahunan()
{
    $tahun_ajaran = TahunAjaran::where('semester', '1')->get();
    $hasil = [];

    // Ambil semua siswa yang memiliki kelas
    $anggota_kelas_list = AnggotaKelas::with('siswa')->get();

    $total_tagihan_semua = 0;
    $total_dibayar_semua = 0;
    $total_sisa_semua = 0;
    $jumlah_siswa_belum_lunas = 0;

    foreach ($anggota_kelas_list as $anggota) {
        $siswa = $anggota->siswa;
        $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $anggota->kelas->tahun_ajaran_id)->get();

        $sisa_siswa = 0;
        $total_tagihan_siswa = 0;
        $total_dibayar_siswa = 0;

        foreach ($tagihan_list as $tagihan) {
            $total_dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota->id)
                ->where('tagihan_tahunan_id', $tagihan->id)
                ->sum('jumlah_bayar');

            $sisa = $tagihan->jumlah - $total_dibayar;

            $total_tagihan_siswa += $tagihan->jumlah;
            $total_dibayar_siswa += $total_dibayar;
            $sisa_siswa += $sisa;
        }

        $hasil[] = [
            'nis' => $siswa->nis,
            'nama' => $siswa->nama_lengkap,
            'kelas' => $anggota->kelas->nama_kelas ?? '-',
            'total_tagihan' => $total_tagihan_siswa,
            'total_dibayar' => $total_dibayar_siswa,
            'sisa_tagihan' => $sisa_siswa,
            'status' => $sisa_siswa == 0 ? 'Lunas' : 'Belum Lunas'
        ];

        $total_tagihan_semua += $total_tagihan_siswa;
        $total_dibayar_semua += $total_dibayar_siswa;
        $total_sisa_semua += $sisa_siswa;

        if ($sisa_siswa > 0) {
            $jumlah_siswa_belum_lunas++;
        }
    }

    return view('laporan.tagihan_tahunan', compact(
        'hasil',
        'tahun_ajaran',
        'total_tagihan_semua',
        'total_dibayar_semua',
        'total_sisa_semua',
        'jumlah_siswa_belum_lunas'
    ));
}

}
