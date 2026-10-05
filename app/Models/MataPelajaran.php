<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataPelajaran extends Model
{
    use HasFactory;
    protected $table = 'mata_pelajaran';
    protected $fillable = [
        'tahun_ajaran_id',
        'kategori_mata_pelajaran_id',
        'nama_mapel',
        'ringkasan_mapel',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id');
    }
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriMataPelajaran::class, 'kategori_mata_pelajaran_id', 'id');
    }
}
