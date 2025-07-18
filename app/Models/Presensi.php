<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    protected $table = 'presensi';
    protected $fillable = [
        'anggota_kelas_id',
        'tanggal',
        'status',
        'terlambat',
        'menit_terlambat',
    ];

    protected $dates = ['tanggal'];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }    

}
