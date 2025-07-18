<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Siswa;
use App\Models\TagihanTahunan;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $tahun_ajaran = TahunAjaran::whereSemester(1)->latest()->first();
            $anggota_kelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahun_ajaran) {
                            $query->where('tahun_ajaran_id', $tahun_ajaran->id);
                        })
                        ->first();

            if (!$anggota_kelas) {
                return redirect()->route('keuangan-tahunan.index')->with('error', 'Data tidak ditemukan!');
            }

            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $riwayat_pembayaran = PembayaranTagihanTahunan::whereAnggotaKelasId($anggota_kelas->id)->get();
        
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $tahun_ajaran->id)
                ->where('jenjang', $anggota_kelas->kelas->jenjang)
                ->where(function ($query) use ($anggota_kelas) {
                    $query->where('kelas', $anggota_kelas->kelas->tingkatan_kelas)
                        ->orWhereNull('kelas');
                })
                ->get();

        
            $hasil_tagihan = $tagihan_list->map(function ($tagihan) use ($anggota_kelas) {
                $total_dibayar = PembayaranTagihanTahunan::whereAnggotaKelasId($anggota_kelas->id)
                    ->whereTagihanTahunanId($tagihan->id)->whereKeterangan('LUNAS')
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
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();
        
            return view('pesertaDidik.keuangan_tahunan.index', compact(
                'tahun_selama_belajar',
                'tahun_ajaran',
                'siswa',
                'hasil_tagihan',
                'tagihan_list',
                'riwayat_pembayaran'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa')) {        
            $tahun_ajaran = TahunAjaran::findOrFail($id);
            $anggota_kelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahun_ajaran) {
                            $query->where('tahun_ajaran_id', $tahun_ajaran->id);
                        })
                        ->first();
            if (!$anggota_kelas) {
                return redirect()->route('keuangan-tahunan.index')->with('error', 'Data tidak ditemukan!');
            }

            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $riwayat_pembayaran = PembayaranTagihanTahunan::whereAnggotaKelasId($anggota_kelas->id)->get();
        
            $tagihan_list = TagihanTahunan::where('tahun_ajaran_id', $tahun_ajaran->id)
                ->where('jenjang', $anggota_kelas->kelas->jenjang)
                ->where(function ($query) use ($anggota_kelas) {
                    $query->where('kelas', $anggota_kelas->kelas->tingkatan_kelas)
                        ->orWhereNull('kelas');
                })
                ->get();

            $hasil_tagihan = $tagihan_list->map(function ($tagihan) use ($anggota_kelas) {
                $total_dibayar = PembayaranTagihanTahunan::whereAnggotaKelasId($anggota_kelas->id)
                    ->whereTagihanTahunanId($tagihan->id)->whereKeterangan('LUNAS')
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
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();
        
            return view('pesertaDidik.keuangan_tahunan.index', compact(
                'tahun_selama_belajar',
                'tahun_ajaran',
                'siswa',
                'hasil_tagihan',
                'tagihan_list',
                'riwayat_pembayaran'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahunAjaran) {
            $query->where('tahun_ajaran_id', $tahunAjaran->id);
        })->where('siswa_nis', Auth::user()->email)->first();

        if (!$anggota_kelas) {
            return redirect()->route('pembayaran-tagihan-tahunan.index')->with('error', 'Anggota kelas tidak ditemukan.');
        }

        $tagihan = TagihanTahunan::findOrFail($request->tagihan_id);

        $total_dibayar = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('tagihan_tahunan_id', $tagihan->id)->whereKeterangan('LUNAS')
            ->sum('jumlah_bayar');

        $cek = PembayaranTagihanTahunan::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('tagihan_tahunan_id', $tagihan->id)
            ->latest()->first();

        if ($cek && $cek->keterangan == "PENDING") {
            return response()->json([
                'status' => 'error',
                'message' => 'Selesaikan dulu pembayaran sebelumnya!',
            ], 422); 
        }

        $sisa_tagihan = $tagihan->jumlah - $total_dibayar;

        if ($request->jumlah_bayar > $sisa_tagihan) {
            return redirect()->route('keuangan-tahunan.index')->with('error', 'Jumlah pembayaran melebihi sisa tagihan (' . number_format($sisa_tagihan, 0, ',', '.') . ').');
        }

        if($request->metode === "lunas"){
            $jumlah_bayar = $sisa_tagihan;
        }else {
            $jumlah_bayar = $request->nominal;
        }
        $order_id = 'TAHUNAN-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $jumlah_bayar,
            ],
            'item_details' => [
                [
                    'id' => 'Tagihan-' . $request->tagihan_id,
                    'name' => 'Biaya ' . $tagihan->jenis,
                    'price' => $jumlah_bayar,
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
                'order_id' => $order_id,
                'anggota_kelas_id' => $anggota_kelas->id,
                'tagihan_tahunan_id' => $tagihan->id,
                'jumlah_bayar' => $jumlah_bayar,
                'tanggal' => now(),
                'keterangan' => 'PENDING',
                'payment_type' => $snap_token,
            ]);
            return response()->json(['snap_token' => $snap_token]);

        } catch (\Exception $e) {
            Log::error('Error bayarTahunan: ' . $e->getMessage());
            return redirect()->route('keuangan-tahunan.index')->with('error', 'Terjadi kesalahan saat membuat pembayaran.');
        }
    }

    public function lanjut($id)
    {
        $data = PembayaranTagihanTahunan::find($id);
        return response()->json(['snap_token' => $data->payment_type]);
    }

    public function cetakInvoice($id)
    {
        $tagihan_tahunan = PembayaranTagihanTahunan::whereOrderId($id)->firstOrFail();
        $pdf = Pdf::loadView('pesertaDidik.keuangan_tahunan.invoice', compact('tagihan_tahunan'));
        return $pdf->stream($id. '.pdf');
    }
}
