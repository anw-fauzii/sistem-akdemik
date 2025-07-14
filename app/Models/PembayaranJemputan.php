<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranJemputan extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_jemputan';
    protected $fillable = [
        'anggota_jemputan_id',
        'bulan_spp_id',
        'jumlah_bayar',
    ];

    public function anggotaJemputan()
    {
        return $this->belongsTo(AnggotaJemputan::class);
    }

    public function bulanSpp()
    {
        return $this->belongsTo(BulanSpp::class);
    }
}
