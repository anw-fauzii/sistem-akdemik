<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaT2Q extends Model
{
    use HasFactory;
    protected $table = 'anggota_t2q';
    protected $fillable = [
        'tingkat',
        'anggota_kelas_id',
        'guru_nipy',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
