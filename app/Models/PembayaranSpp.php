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
        'jemputan',

        'order_id',
        'payment_type',
        'va_number',
        'pdf_url',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function bulanSpp()
    {
        return $this->belongsTo(BulanSpp::class);
    }
}
