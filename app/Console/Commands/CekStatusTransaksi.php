<?php

namespace App\Console\Commands;

use App\Models\PembayaranSpp;
use App\Models\PembayaranTagihanTahunan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CekStatusTransaksi extends Command
{
    protected $signature = 'cek:status-transaksi';
    protected $description = 'Cek status transaksi yang masih pending di Midtrans';
    public function handle()
    {
        $serverKey = config('services.midtrans.server_key');

        $semuaTransaksi = collect()
            ->merge(PembayaranSpp::where('keterangan', 'PENDING')->get())
            ->merge(PembayaranTagihanTahunan::where('keterangan', 'PENDING')->get());

        foreach ($semuaTransaksi as $transaksi) {
            $orderId = $transaksi->order_id;

            try {
                $response = Http::withBasicAuth($serverKey, '')
                    ->get("https://api.sandbox.midtrans.com/v2/{$orderId}/status");

                if ($response->successful()) {
                    $status = $response['transaction_status'];

                    switch ($status) {
                        case 'settlement':
                            $transaksi->keterangan = 'LUNAS';
                            break;
                        case 'expire':
                            $transaksi->keterangan = 'EXPIRED';
                            break;
                        case 'cancel':
                            $transaksi->keterangan = 'DIBATALKAN';
                            break;
                        case 'pending':
                            // tetap pending
                            break;
                        default:
                            $transaksi->keterangan = strtoupper($status);
                            break;
                    }

                    $transaksi->save();
                } else {
                    \Log::warning("Gagal cek status Midtrans: {$orderId} | HTTP Error");
                }
            } catch (\Exception $e) {
                \Log::warning("Gagal cek status Midtrans: {$orderId} | " . $e->getMessage());
            }
        }
    }
}
