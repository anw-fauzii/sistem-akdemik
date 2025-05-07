<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
    }

    // Endpoint untuk mengambil Snap Token
    public function getSnapToken(Request $request)
    {
        $method = $request->input('method'); // menerima metode pembayaran dari frontend

        // Data transaksi
        $transaction_details = [
            'order_id' => 'order-id-' . time(),
            'gross_amount' => 100000,
        ];

        $item_details = [
            [
                'id' => 'item-1',
                'price' => 100000,
                'quantity' => 1,
                'name' => 'Pembayaran Sekolah',
            ]
        ];

        // Buat konfigurasi metode pembayaran jika ada
        $enabled_payments = [];
        if ($method === 'bca_va') {
            $enabled_payments[] = 'bca_va';
        } elseif ($method === 'qris') {
            $enabled_payments[] = 'qris';
        } elseif ($method === 'gopay') {
            $enabled_payments[] = 'gopay';
        }

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'enabled_payments' => $enabled_payments,
        ];

        try {
            $snap_token = Snap::getSnapToken($transaction_data);
            return response()->json(['snap_token' => $snap_token]);
        } catch (\Exception $e) {
            FacadesLog::error('Error generating Snap token: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating Snap token.'], 500);
        }
    }
}
