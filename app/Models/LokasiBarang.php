<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiBarang extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'lokasi_barang';

    protected $fillable = [
        'id',
        'unit',
        'nama_lokasi_barang',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'lokasi_barang_id');
    }
}
