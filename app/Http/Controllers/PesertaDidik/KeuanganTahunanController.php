<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Siswa;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;

class KeuanganTahunanController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
    }

    public function index()
    {
        if (user()?->hasRole('siswa')) {        
            $tahun_ajaran = TahunAjaran::latest()->first();
            $anggota_kelas = AnggotaKelas::whereTahunAjaranId($tahun_ajaran->id)
                        ->whereSiswaNis(Auth::user()->email)
                        ->firstOrFail();

            if (!$anggota_kelas) {
                return redirect()->route('keuangan-tahunan.index')->with('error', 'Data tidak ditemukan!');
            }

            $siswa = Siswa::where('nis', Auth::user()->email)->first();
        
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $tahun_ajaran->id)
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
            $tahun_selama_belajar = AnggotaKelas::whereSiswaNis(Auth::user()->email)->get();
        
            return view('pesertaDidik.keuangan_tahunan.index', compact(
                'tahun_selama_belajar',
                'tahun_ajaran',
                'siswa',
                'hasil_tagihan',
                'tagihan_list'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa')) {        
            $tahun_ajaran = TahunAjaran::find($id);
            $anggota_kelas = AnggotaKelas::whereTahunAjaranId($tahun_ajaran->id)
                        ->whereSiswaNis(Auth::user()->email)
                        ->firstOrFail();

            if (!$anggota_kelas) {
                return redirect()->route('keuangan-tahunan.index')->with('error', 'Data tidak ditemukan!');
            }

            $siswa = Siswa::where('nis', Auth::user()->email)->first();
        
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $tahun_ajaran->id)
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
            $tahun_selama_belajar = AnggotaKelas::whereSiswaNis(Auth::user()->email)->get();
        
            return view('pesertaDidik.keuangan_tahunan.index', compact(
                'tahun_selama_belajar',
                'tahun_ajaran',
                'siswa',
                'hasil_tagihan',
                'tagihan_list'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        $id=2;
        $tahunAjaran = TahunAjaran::latest()->first();
            $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })->where('siswa_nis', Auth::user()->email)->first();

        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Anggota kelas tidak ditemukan.');
        }

        $tagihan = TagihanTahunan::findOrFail($id);

        $total_dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('tagihan_tahunan_id', $tagihan->id)
            ->sum('jumlah_bayar');

        $sisa_tagihan = $tagihan->jumlah - $total_dibayar;

        if ($request->jumlah_bayar > $sisa_tagihan) {
            return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Jumlah pembayaran melebihi sisa tagihan (' . number_format($sisa_tagihan, 0, ',', '.') . ').');
        }        
        $order_id = 'ORDER-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->jumlah_bayar,
            ],
            'item_details' => [
                [
                    'id' => 'Tagihan-' . $id,
                    'name' => 'Biaya ' . $tagihan->jenis,
                    'price' => $request->jumlah_bayar,
                    'quantity' => 1,
                ],
            ],
            'customer_details' => [
                'first_name' => $anggota_kelas->siswa_nis,
                'phone' => $anggota_kelas->siswa->telepon ?? '',
            ],
        ];

        try {
            $snap_token = Snap::getSnapToken($params);
            
            PembayaranTagihanTahunan::create([
                'anggota_kelas_id' => $anggota_kelas->id,
                'tagihan_tahunan_id' => $tagihan->id,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tanggal' => now(),
            ]);

            return response()->json(['snap_token' => $snap_token]);

        } catch (\Exception $e) {
            Log::error('Error bayarTahunan: ' . $e->getMessage());
            return redirect()->route('keuangan-tahunan.index')->with('error', 'Terjadi kesalahan saat membuat pembayaran.');
        }
    }
}
