<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAdministrasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_administrasi';

    protected $fillable = [
        'nama_kategori',
        'jenis',
        'semester',
    ];

    protected $casts = [
        'semester' => 'boolean',
    ];

    public function administrasi_guru(){
        return $this->hasMany(AdministrasiGuru::class);
    }

    public function administrasi_kelas(){
        return $this->hasMany(AdministrasiKelas::class);
    }
}
