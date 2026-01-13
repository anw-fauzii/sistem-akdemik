<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrasiKelas extends Model
{
    use HasFactory;
    protected $table = 'administrasi_kelas';
    protected $fillable = [
        'tahun_ajaran_id',
        'kelas_id',
        'kategori_administrasi_id',
        'keterangan',
        'link',
        'status',
    ];

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kategori_administrasi()
    {
        return $this->belongsTo(KategoriAdministrasi::class);
    }
}
