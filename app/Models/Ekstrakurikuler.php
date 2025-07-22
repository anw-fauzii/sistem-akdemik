<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    use HasFactory;
    protected $table = 'ekstrakurikuler';
    protected $fillable = [
        'tahun_ajaran_id',
        'guru_nipy',
        'nama_ekstrakurikuler',
        'biaya'
    ];

    public function tapel()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    public function anggotaEkstrakurikuler()
    {
        return $this->hasMany(AnggotaEkstrakurikuler::class);
    }
}
