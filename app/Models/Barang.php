<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'nama_barang',
        'kategori_barang_id',
        'lokasi_barang_id',
        'merk',
        'tipe',
        'nomor_seri',
        'tahun_perolehan',
        'harga_perolehan',
        'kondisi',
        'status',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiBarang::class, 'lokasi_barang_id');
    }
}