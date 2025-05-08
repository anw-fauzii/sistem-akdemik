<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanTahunan extends Model
{
    use HasFactory;
    protected $table = 'tagihan_tahunan';
    protected $fillable = [
        'tahun_ajaran_id',
        'jenis',
        'jumlah',
        'kelas',
        'jenjang',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembayaranTagihanTahunan()
    {
        return $this->hasMany(PembayaranTagihanTahunan::class);
    }
}
