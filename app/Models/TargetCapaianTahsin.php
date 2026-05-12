<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetCapaianTahsin extends Model
{
    use HasFactory;

    protected $table = 'target_capaian_tahsin';

    protected $guarded = ['id'];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function daftarJilid()
    {
        return $this->belongsTo(DaftarJilid::class, 'daftar_jilid_id');
    }

    public function surahAlquran()
    {
        return $this->belongsTo(SurahAlquran::class, 'surah_alquran_id');
    }
}