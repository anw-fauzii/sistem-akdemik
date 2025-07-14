<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaJemputan extends Model
{
    use HasFactory;
    protected $table = 'anggota_jemputan';
    protected $fillable = [
        'jemputan_id',
        'anggota_kelas_id',
        'keterangan',
        'diskon',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function jemputan()
    {
        return $this->belongsTo(Jemputan::class);
    }

    public function pembayaranBulan()
    {
        return $this->hasMany(PembayaranJemputan::class);
    }
}
