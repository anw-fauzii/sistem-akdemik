<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YaumiyahTahsin extends Model
{
    use HasFactory; 
    protected $table = 'yaumiyah_tahsin';

    protected $fillable = [
        'anggota_t2q_id',
        'daftar_jilid_id',
        'surah_alquran_id',
        'angka_arab_id',
        'tanggal',
        'nilai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'integer',
    ];

    public function anggotaT2Q(): BelongsTo
    {
        return $this->belongsTo(AnggotaT2Q::class, 'anggota_t2q_id');
    }

    public function daftarJilid(): BelongsTo
    {
        return $this->belongsTo(DaftarJilid::class, 'daftar_jilid_id');
    }

    public function surahAlquran(): BelongsTo
    {
        return $this->belongsTo(SurahAlquran::class, 'surah_alquran_id');
    }

    public function angkaArab(): BelongsTo
    {
        return $this->belongsTo(AngkaArab::class, 'angka_arab_id');
    }

    public function nilaiArab(): BelongsTo
    {
        return $this->belongsTo(AngkaArab::class, 'nilai', 'id');
    }
}