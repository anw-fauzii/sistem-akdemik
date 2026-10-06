<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'kategori_barang';

    protected $fillable = [
        'id',
        'nama_kategori_barang',
    ];
    
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_barang_id');
    }
}
