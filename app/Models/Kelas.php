<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $fillable = [
        'tahun_ajaran_id',
        'guru_nipy',
        'pendamping_nipy',
        'tingkatan_kelas',
        'nama_kelas',
        'romawi',
        'spp',
        'biaya_makan',
    ];

    public function tapel()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function pendamping(){
        return $this->belongsTo(Guru::class,'pendamping_nipy','nipy');
    }
}
