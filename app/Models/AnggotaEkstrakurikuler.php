<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaEkstrakurikuler extends Model
{
    use HasFactory;
    protected $table = 'anggota_ekstrakurikuler';
    protected $fillable = [
        'anggota_kelas_id',
        'ekstrakurikuler_id',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }
}
