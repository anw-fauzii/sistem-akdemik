<?php

namespace App\Http\Controllers\PesertaDidik;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Presensi;
use App\Models\PresensiEkstrakurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return view('pesertaDidik.keuangan_spp.index', compact(
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
            return view('pesertaDidik.keuangan_spp.index', compact(
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
            $cek_pembayaran = PembayaranSpp::where('anggota_kelas_id', $anggota_kelas->id)
            ->where('bulan_spp_id', $id)
            ->first();
            if ($cek_pembayaran) {
                return response()->json(['snap_token' => $cek_pembayaran->payment_type]);
            }

            $kelas = $anggota_kelas->kelas;
            $nominal_spp = $kelas->spp ?? 0;
            $biaya_makan = $kelas->biaya_makan ?? 0;

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
            if ($max_sakit_beruntun > 7) {
                $biaya_makan_potongan *= 0.75;
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

            $total_pembayaran = $nominal_spp + $biaya_makan_potongan + $tambahan + $total_ekskul;

            $order_id = 'ORDER-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $total_pembayaran,
                ],
                'item_details' => [
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
                        'id' => 'BIAYA_MAKAN',
                        'name' => 'Biaya Ekstrakurikuler',
                        'price' => $total_ekskul,
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

                PembayaranSpp::create([
                    'anggota_kelas_id' => $anggota_kelas->id,
                    'bulan_spp_id' => $id,
                    'nominal_spp' => $nominal_spp,
                    'biaya_makan' => $biaya_makan_potongan + $tambahan,
                    'tambahan' => $tambahan,
                    'ekstrakurikuler' => $total_ekskul,
                    'total_pembayaran' => $total_pembayaran,
                    'keterangan' => 'Pending',
                    'order_id' => $order_id,
                    'payment_type' => $snap_token,
                    'transaction_status' => 'pending',
                ]);

                return response()->json(['snap_token' => $snap_token]);

            } catch (\Exception $e) {
                return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat membuat pembayaran.');
            }

        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
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

        // Temukan transaksi berdasarkan order_id
        $payment = PembayaranSpp::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status sesuai transaksi
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            $payment->transaction_status = 'success';
            $payment->keterangan = 'Lunas';
        } elseif ($transactionStatus == 'pending') {
            $payment->transaction_status = 'pending';
            $payment->keterangan = 'Menunggu Pembayaran';
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $payment->transaction_status = 'failed';
            $payment->keterangan = 'Gagal';
        }

        $payment->save();

        return response()->json(['message' => 'Callback handled']);
    }
}
