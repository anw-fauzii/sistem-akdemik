<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Siswa;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PembayaranTagihanTahunanController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $tahun_ajaran = TahunAjaran::whereSemester('1')->get();
            $siswa_list = Siswa::whereStatus(TRUE)->get();
            return view('pembayaran_tagihan_tahunan.index', compact('tahun_ajaran', 'siswa_list'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_nis' => 'required',
            'tahun_ajaran_id' => 'required',
            'tagihan_tahunan_id' => 'required',
            'jumlah_bayar' => 'required|numeric|min:1',
        ], [
            'jumlah_bayar.required' => 'Jumlah pembayaran wajib diisi.',
            'jumlah_bayar.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah pembayaran minimal 1.',
        ]);
        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($request) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        })->where('siswa_nis', $request->siswa_nis)->first();

        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Anggota kelas tidak ditemukan.');
        }

        $tagihan = TagihanTahunan::findOrFail($request->tagihan_tahunan_id);

        $total_dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('tagihan_tahunan_id', $tagihan->id)
            ->sum('jumlah_bayar');

        $sisa_tagihan = $tagihan->jumlah - $total_dibayar;

        if ($request->jumlah_bayar > $sisa_tagihan) {
            return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Jumlah pembayaran melebihi sisa tagihan (' . number_format($sisa_tagihan, 0, ',', '.') . ').');
        }        

        PembayaranTagihanTahunan::create([
            'anggota_kelas_id' => $anggota_kelas->id,
            'order_id' => 'TAHUNAN-' . time(),
            'tagihan_tahunan_id' => $tagihan->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'keterangan' => "LUNAS",
            'tanggal' => now(),
        ]);

        return redirect()->route('pembayaran-tagihan-tahunan.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function cari(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $request->validate([
                'tahun_ajaran_id' => 'required',
                'siswa_nis' => 'required',
            ]);
        
            $tahun_ajaran_id = $request->tahun_ajaran_id;
            $siswa_nis = $request->siswa_nis;
        
            $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahun_ajaran_id) {
                $query->whereTahunAjaranId($tahun_ajaran_id);
            })->whereSiswaNis($siswa_nis)->first();
            if (!$anggota_kelas) {
                return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Data tidak ditemukan!');
            }
        
            $siswa = Siswa::where('nis', $siswa_nis)->first();
        
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $tahun_ajaran_id)
                ->where('jenjang', $anggota_kelas->kelas->jenjang)
                ->where(function ($query) use ($anggota_kelas) {
                    $query->where('kelas', $anggota_kelas->kelas->tingkatan_kelas)
                        ->orWhereNull('kelas');
                })
                ->get();

        
            $hasil_tagihan = $tagihan_list->map(function ($tagihan) use ($anggota_kelas) {
                $total_dibayar = PembayaranTagihanTahunan::whereAnggotaKelasId($anggota_kelas->id)
                    ->whereTagihanTahunanId($tagihan->id)
                    ->sum('jumlah_bayar');
        
                return [
                    'id' => $tagihan->id,
                    'jenis' => $tagihan->jenis,
                    'total_tagihan' => $tagihan->jumlah,
                    'total_dibayar' => $total_dibayar,
                    'sisa_tagihan' => $tagihan->jumlah - $total_dibayar,
                    'status' => ($tagihan->jumlah <= $total_dibayar) ? 'Lunas' : 'Belum Lunas',
                ];
            });
            $tahun_ajaran = TahunAjaran::where('semester', '1')->get();
        
            $siswa_list = Siswa::whereStatus(TRUE)->get();
        
            return view('pembayaran_tagihan_tahunan.index', compact(
                'tahun_ajaran',
                'siswa',
                'siswa_list',
                'tahun_ajaran_id',
                'siswa_nis',
                'hasil_tagihan',
                'tagihan_list'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
