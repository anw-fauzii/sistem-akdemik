<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kesehatan extends Model
{
    use HasFactory;
    protected $table = 'kesehatan'; 
    protected $fillable = [
        'anggota_kelas_id',
        'bulan_spp_id',
        'tb',      
        'bb',      
        'lila',  
        'lika',     
        'lp',     
        'mata',
        'telinga',
        'gigi',
        'tensi',
        'hasil',
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
