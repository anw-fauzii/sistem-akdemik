<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaJemputan;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranJemputan;
use App\Models\PembayaranSpp;
use App\Models\PembayaranTagihanTahunan;
use App\Models\Presensi;
use App\Models\PresensiEkstrakurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\TarifSpp;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;

class KeuanganController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
    }

    public function index()
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                            $query->where('tahun_ajaran_id', $tahunAjaran->id);
                        })
                        ->first();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
        
            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $nominal_biaya = TarifSpp::find($siswa->tarif_spp_id);
            $spp = $nominal_biaya ? $nominal_biaya->spp : 0;
            $biaya_makan = $nominal_biaya ? $nominal_biaya->biaya_makan : 0;
            $snack = $nominal_biaya ? $nominal_biaya->snack : 0;

            $riwayat_pembayaran = PembayaranSpp::whereAnggotaKelasId($anggotaKelas->id)->get();
        
            $tagihan_spp = BulanSpp::leftJoin('pembayaran_spp', function ($join) use ($anggotaKelas) {
                $join->on('bulan_spp.id', '=', 'pembayaran_spp.bulan_spp_id')
                    ->where('pembayaran_spp.anggota_kelas_id', '=', $anggotaKelas->id)
                    ->where('pembayaran_spp.keterangan', 'LUNAS');
            })->leftJoin('anggota_jemputan', function ($join) use ($anggotaKelas) {
                $join->on('anggota_jemputan.anggota_kelas_id', '=', DB::raw($anggotaKelas->id));
            })
            ->leftJoin('pembayaran_jemputan', function ($join) {
                $join->on('pembayaran_jemputan.anggota_jemputan_id', '=', 'anggota_jemputan.id')
                    ->on('pembayaran_jemputan.bulan_spp_id', '=', 'bulan_spp.id');
            })->whereTahunAjaranId($tahunAjaran->id)
            ->select(
                'bulan_spp.*',
                'pembayaran_spp.keterangan',
                'pembayaran_spp.id as pembayaran_id',
                'pembayaran_jemputan.jumlah_bayar as jemputan_bayar'
            )
            ->get()->where('keterangan', '!=', 'EXPIRED')
            ->map(function ($tagihan) use ($anggotaKelas, $biaya_makan, $snack) {
                $bulan = date('m', strtotime($tagihan->bulan_angka));
                $absen_sakit = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulan)
                    ->orderBy('tanggal', 'asc')
                    ->pluck('tanggal')
                    ->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal));
        
                $potongan_makan = 0;
                $potongan_snack = 0;
                if ($absen_sakit->count() >= 7) {
                    $sorted = $absen_sakit->sort()->values();
                    $streak = 1;
                    for ($i = 1; $i < $sorted->count(); $i++) {
                        $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                        if ($diff == 1) {
                            $streak++;
                            if ($streak >= 7) {
                                $potongan_makan = 0.25 * $biaya_makan;
                                $potongan_snack = 0.25 * $snack;
                                break;
                            }
                        } else {
                            $streak = 1;
                        }
                    }
                }
        
                $total_biaya_makan = $biaya_makan - $potongan_makan;
                $total_snack = $snack - $potongan_snack;
        
                $biaya_ekskul_bulan_ini = $anggotaKelas->ekstrakurikuler->sum(function ($item) use ($bulan) {
                    $pernah_hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulan)
                        ->exists();
        
                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });
        
                $tagihan->jumlah_absen = $absen_sakit->count();
                $tagihan->total_biaya_makan = $total_biaya_makan;
                $tagihan->total_snack = $total_snack;
                $tagihan->biaya_ekskul = $biaya_ekskul_bulan_ini;
                $tagihan->biaya_jemputan = $tagihan->jemputan_bayar ?? 0; 
        
                return $tagihan;
            });

            $total_ekskul = $tagihan_spp->sum('biaya_ekskul');
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();

            return view('pesertaDidik.keuangan_spp.index', compact(
                'tahunAjaran',
                'siswa',
                'tagihan_spp',
                'spp',
                'biaya_makan',
                'total_ekskul',
                'tahun_selama_belajar',
                'riwayat_pembayaran'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                    $query->where('tahun_ajaran_id', $tahunAjaran->id);
                })
                ->first();

            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $riwayat_pembayaran = PembayaranSpp::whereAnggotaKelasId($anggotaKelas->id)->get();
            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $nominal_biaya = TarifSpp::find($siswa->tarif_spp_id);
            $spp = $nominal_biaya ? $nominal_biaya->spp : 0;
            $biaya_makan = $nominal_biaya ? $nominal_biaya->biaya_makan : 0;
            $snack = $nominal_biaya ? $nominal_biaya->snack : 0;
        
            $tagihan_spp = BulanSpp::leftJoin('pembayaran_spp', function ($join) use ($anggotaKelas) {
                $join->on('bulan_spp.id', '=', 'pembayaran_spp.bulan_spp_id')
                    ->where('pembayaran_spp.anggota_kelas_id', '=', $anggotaKelas->id)
                    ->where('pembayaran_spp.keterangan', 'LUNAS');
            })->leftJoin('anggota_jemputan', function ($join) use ($anggotaKelas) {
                $join->on('anggota_jemputan.anggota_kelas_id', '=', DB::raw($anggotaKelas->id));
            })
            ->leftJoin('pembayaran_jemputan', function ($join) {
                $join->on('pembayaran_jemputan.anggota_jemputan_id', '=', 'anggota_jemputan.id')
                    ->on('pembayaran_jemputan.bulan_spp_id', '=', 'bulan_spp.id');
            })->whereTahunAjaranId($tahunAjaran->id)
            ->select(
                'bulan_spp.*',
                'pembayaran_spp.keterangan',
                'pembayaran_spp.id as pembayaran_id',
                'pembayaran_jemputan.jumlah_bayar as jemputan_bayar'
            )
            ->get()
            ->map(function ($tagihan) use ($anggotaKelas, $biaya_makan, $snack) {
                $bulan = date('m', strtotime($tagihan->bulan_angka));
                $absen_sakit = Presensi::where('anggota_kelas_id', $anggotaKelas->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulan)
                    ->orderBy('tanggal', 'asc')
                    ->pluck('tanggal')
                    ->map(fn($tanggal) => \Carbon\Carbon::parse($tanggal));
        
                $potongan_makan = 0;
                $potongan_snack = 0;
                if ($absen_sakit->count() >= 7) {
                    $sorted = $absen_sakit->sort()->values();
                    $streak = 1;
                    for ($i = 1; $i < $sorted->count(); $i++) {
                        $diff = $sorted[$i]->diffInDays($sorted[$i - 1]);
                        if ($diff == 1) {
                            $streak++;
                            if ($streak >= 7) {
                                $potongan_makan = 0.25 * $biaya_makan;
                                $potongan_snack = 0.25 * $snack;
                                break;
                            }
                        } else {
                            $streak = 1;
                        }
                    }
                }
        
                $total_biaya_makan = $biaya_makan - $potongan_makan;
                $total_snack = $snack - $potongan_snack;
        
                $biaya_ekskul_bulan_ini = $anggotaKelas->ekstrakurikuler->sum(function ($item) use ($bulan) {
                    $pernah_hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $item->id)
                        ->where('status', 'hadir')
                        ->whereMonth('tanggal', $bulan)
                        ->exists();
        
                    return $pernah_hadir ? ($item->ekstrakurikuler->biaya ?? 0) : 0;
                });
        
                $tagihan->jumlah_absen = $absen_sakit->count();
                $tagihan->total_biaya_makan = $total_biaya_makan;
                $tagihan->total_snack = $total_snack;
                $tagihan->biaya_ekskul = $biaya_ekskul_bulan_ini;
                $tagihan->biaya_jemputan = $tagihan->jemputan_bayar ?? 0; 
        
                return $tagihan;
            });
        
            $total_ekskul = $tagihan_spp->sum('biaya_ekskul');
            $tahun_selama_belajar = AnggotaKelas::with('kelas.tahun_ajaran')
                ->whereSiswaNis(Auth::user()->email)
                ->get();
            return view('pesertaDidik.keuangan_spp.index', compact(
                'tahunAjaran',
                'siswa',
                'tagihan_spp',
                'spp',
                'biaya_makan',
                'total_ekskul',
                'tahun_selama_belajar',
                'riwayat_pembayaran'
            ));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function bayarSpp($id)
    {
        if (user()?->hasRole('siswa')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggota_kelas = AnggotaKelas::whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })->where('siswa_nis', Auth::user()->email)->first();

            if (!$anggota_kelas) {
                return redirect()->route('keuangan.index')->with('error', 'Data siswa tidak ditemukan!');
            }
            $cek = PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('bulan_spp_id', $id)
            ->latest()->first();

            if ($cek && $cek->keterangan == "PENDING") {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Selesaikan dulu pembayaran sebelumnya!',
                ], 422); 
            }

            $kelas = $anggota_kelas->kelas;
            $siswa = Siswa::where('nis', Auth::user()->email)->first();
            $nominal_biaya = TarifSpp::find($siswa->tarif_spp_id);
            $nominal_spp = $nominal_biaya->spp ?? 0;
            $biaya_makan = $nominal_biaya->biaya_makan ?? 0;
            $snack = $nominal_biaya->snack ?? 0;

            $bulan_spp = BulanSpp::find($id);
            if (!$bulan_spp) {
                return redirect()->route('keuangan.index')->with('error', 'Bulan SPP tidak valid!');
            }

            $bulan = Carbon::parse($bulan_spp->bulan_angka)->month;
            $tambahan = $bulan_spp->tambahan ?? 0;

            $sakit_beruntun = Presensi::where('anggota_kelas_id', $anggota_kelas->id)
                ->where('status', 'sakit')
                ->whereMonth('tanggal', $bulan)
                ->orderBy('tanggal', 'asc')
                ->get()
                ->pluck('tanggal')
                ->map(fn($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
                ->toArray();

            $max_sakit_beruntun = 0;
            $current_streak = 1;

            for ($i = 1; $i < count($sakit_beruntun); $i++) {
                $prev_date = Carbon::parse($sakit_beruntun[$i - 1]);
                $current_date = Carbon::parse($sakit_beruntun[$i]);

                if ($current_date->diffInDays($prev_date) == 1) {
                    $current_streak++;
                } else {
                    $max_sakit_beruntun = max($max_sakit_beruntun, $current_streak);
                    $current_streak = 1;
                }
            }
            $max_sakit_beruntun = max($max_sakit_beruntun, $current_streak);

            $biaya_makan_potongan = $biaya_makan;
            $biaya_snack_potongan = $snack;
            if ($max_sakit_beruntun > 7) {
                $biaya_makan_potongan *= 0.75;
                $biaya_snack_potongan *= 0.75;
            }

            $total_ekskul = 0;

            $ekskul_aktif = $anggota_kelas->ekstrakurikuler;
            foreach ($ekskul_aktif as $ekskul_item) {
                $hadir = PresensiEkstrakurikuler::where('anggota_ekstrakurikuler_id', $ekskul_item->id)
                    ->where('status', 'hadir')
                    ->whereMonth('tanggal', $bulan)
                    ->exists();

                if ($hadir && $ekskul_item->ekstrakurikuler) {
                    $total_ekskul += $ekskul_item->ekstrakurikuler->biaya ?? 0;
                }
            }

            $pembayaranJemputan = 0;
            $anggotaJemputan = AnggotaJemputan::where('anggota_kelas_id', $anggota_kelas->id)
                ->whereHas('jemputan', function ($q) use ($kelas) {
                    $q->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
                })
                ->first();

            if ($anggotaJemputan) {
                $bayarJemputan = PembayaranJemputan::where('anggota_jemputan_id', $anggotaJemputan->id)
                    ->where('bulan_spp_id', $id)
                    ->first();

                if ($bayarJemputan) {
                    $pembayaranJemputan = $bayarJemputan->jumlah_bayar;
                }
            }
            $total_pembayaran = $nominal_spp + $biaya_makan_potongan + $tambahan + $total_ekskul + $pembayaranJemputan + $biaya_snack_potongan;

            $order_id = 'SPP-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $total_pembayaran + 5000,
                ],
                'item_details' => [
                    [
                        'id' => 'Admin',
                        'name' => 'Admin',
                        'price' => '5000',
                        'quantity' => 1,
                    ],
                    [
                        'id' => 'SPP-' . $id,
                        'name' => 'SPP ' . $bulan_spp->nama_bulan,
                        'price' => $nominal_spp,
                        'quantity' => 1,
                    ],
                    [
                        'id' => 'BIAYA_MAKAN',
                        'name' => 'Biaya Makan',
                        'price' => $biaya_makan_potongan + $tambahan,
                        'quantity' => 1,
                    ],
                    [
                        'id' => 'BIAYA_SNACK',
                        'name' => 'Snack',
                        'price' => $biaya_snack_potongan,
                        'quantity' => 1,
                    ],
                    [
                        'id' => 'BIAYA_EKSTRAKURIKULER',
                        'name' => 'Biaya Ekstrakurikuler',
                        'price' => $total_ekskul,
                        'quantity' => 1,
                    ],
                    [
                        'id' => 'BIAYA_JEMPUTAN',
                        'name' => 'Biaya Jemputan',
                        'price' => $pembayaranJemputan,
                        'quantity' => 1,
                    ],
                ],
                'customer_details' => [
                    'first_name' => $anggota_kelas->siswa->nama_lengkap,
                    'phone' => $anggota_kelas->siswa_nis ?? '',
                ],
            ];

            try {
                $snap_token = Snap::getSnapToken($params);

                PembayaranSpp::create([
                    'anggota_kelas_id' => $anggota_kelas->id,
                    'bulan_spp_id' => $id,
                    'nominal_spp' => $nominal_spp,
                    'biaya_makan' => $biaya_makan_potongan + $tambahan,
                    'ekstrakurikuler' => $total_ekskul,
                    'jemputan' => $pembayaranJemputan,
                    'snack' => $biaya_snack_potongan,
                    'total_pembayaran' => $total_pembayaran,
                    'keterangan' => 'PENDING',
                    'order_id' => $order_id,
                    'payment_type' => $snap_token,
                ]);

                return response()->json(['snap_token' => $snap_token]);

            } catch (\Exception $e) {
                return redirect()->route('keuangan-spp.index')->with('error', 'Terjadi kesalahan saat membuat pembayaran.');
            }

        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function lanjut($id)
    {
        $data = PembayaranSpp::find($id);
        return response()->json(['snap_token' => $data->payment_type]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = $request->input('signature_key');
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $transactionStatus = $request->input('transaction_status');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = null;

        if (str_starts_with($orderId, 'SPP-')) {
            $transaksi = PembayaranSpp::where('order_id', $orderId)->first();

        } elseif (str_starts_with($orderId, 'TAHUNAN-')) {
            $transaksi = PembayaranTagihanTahunan::where('order_id', $orderId)->first();
        } else {
            return response()->json(['message' => 'Order ID tidak dikenali'], 400);
        }

        switch ($transactionStatus) {
            case 'settlement':
                $transaksi->keterangan = 'LUNAS';
                break;
            case 'pending':
                $transaksi->keterangan = 'PENDING';
                break;
            case 'expire':
                $transaksi->keterangan = 'EXPIRED';
                break;
            case 'cancel':
                $transaksi->keterangan = 'DIBATALKAN';
                break;
            default:
                $transaksi->keterangan = strtoupper($transactionStatus);
                break;
        }

        $transaksi->save();

        return response()->json(['message' => 'Callback berhasil diproses']);
    }

    public function cetakInvoice($id)
    {
        $tagihan_spp = PembayaranSpp::whereOrderId($id)->firstOrFail();
        $pdf = Pdf::loadView('pesertaDidik.keuangan_spp.invoice', compact('tagihan_spp'));
        return $pdf->stream($id. '.pdf');
    }
}
