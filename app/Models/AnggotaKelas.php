<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelas extends Model
{
    use HasFactory;
    protected $table = 'anggota_kelas';
    protected $fillable = [
        'siswa_nis',
        'kelas_id',
        'tapel_id',
        'pendaftaran',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nis', 'nis');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

}
