<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulanSpp extends Model
{
    use HasFactory;
    protected $table = 'bulan_spp';
    protected $fillable = [
        'nama_bulan',
        'bulan_angka',
        'tambahan',
        'tahun_ajaran_id'
    ];

    protected $dates = ['bulan_angka'];

    public function pembayaranSpp()
    {
        return $this->hasMany(PembayaranSpp::class, 'bulan_spp_id');
    }
}
