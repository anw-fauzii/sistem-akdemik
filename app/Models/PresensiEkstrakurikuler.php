<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiEkstrakurikuler extends Model
{
    use HasFactory;
    protected $table = 'presensi_ekstrakurikuler';
    protected $fillable = [
        'anggota_ekstrakurikuler_id',
        'tanggal',
        'status',
    ];

    protected $dates = ['tanggal'];

    public function anggotaEkstrakurikuler()
    {
        return $this->belongsTo(AnggotaEkstrakurikuler::class);
    }   
}
