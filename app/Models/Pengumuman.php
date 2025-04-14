<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    protected $table = 'pengumuman';
    protected $fillable = [
        'tahun_ajaran_id',
        'judul',
        'isi',
        'tanggal',
        'tahun_ajaran_id',
    ];
    protected $dates = ['tanggal'];
    
    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
