<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrasiGuru extends Model
{
    use HasFactory;
    protected $table = 'administrasi_guru';
    protected $fillable = [
        'tahun_ajaran_id',
        'guru_nipy',
        'kategori_administrasi_id',
        'keterangan',
        'link',
        'status',
    ];

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
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
