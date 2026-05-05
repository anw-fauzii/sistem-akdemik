<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KedisiplinanSiswa extends Model
{
    use HasFactory;

    protected $table = 'kedisiplinan_siswa';

    protected $fillable = [
        'anggota_kelas_id',
        'kedisiplinan_poin_id',
        'tanggal_kejadian',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    public function kedisiplinanPoin(): BelongsTo
    {
        return $this->belongsTo(KedisiplinanPoin::class, 'kedisiplinan_poin_id');
    }

    public function anggotaKelas(): BelongsTo
    {
        return $this->belongsTo(AnggotaKelas::class, 'anggota_kelas_id');
    }
}