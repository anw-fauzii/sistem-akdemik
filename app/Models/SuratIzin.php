<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratIzin extends Model
{
    use HasFactory;
    protected $table = 'surat_izin';

    protected $fillable = [
        'anggota_kelas_id',
        'tanggal',
        'jenis',
        'keterangan',
        'file'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function anggotaKelas()
    {
        return $this->belongsTo(AnggotaKelas::class);
    }
}
