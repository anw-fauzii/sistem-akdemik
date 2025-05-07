<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_spp';
    protected $fillable = [
        'anggota_kelas_id',
        'bulan_spp_id',
        'nominal_spp',
        'biaya_makan',
        'total_pembayaran',
        'keterangan',
        'ekstrakurikuler',
    
        // Midtrans-related fields
        'order_id',
        'payment_type',
        'transaction_status',
        'transaction_time',
        'va_number',        // jika pakai VA
        'pdf_url',          // URL bukti pembayaran
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function bulanSPP()
    {
        return $this->belongsTo(BulanSpp::class);
    }
}
