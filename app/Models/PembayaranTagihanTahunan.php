<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTagihanTahunan extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_tagihan_tahunan';
    protected $fillable = [
        'anggota_kelas_id',
        'tagihan_tahunan_id',
        'jumlah_bayar',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function tagihanTahunan()
    {
        return $this->belongsTo(TagihanTahunan::class);
    }

}
