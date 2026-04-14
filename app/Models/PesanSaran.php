<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanSaran extends Model
{
    use HasFactory;

    protected $table = 'pesan_saran';

    protected $fillable = [
        'siswa_nis',
        'subjek',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nis', 'nis');
    }

    public function detail()
    {
        return $this->hasMany(PesanSaranDetail::class, 'pesan_saran_id');
    }

    public function latestDetail()
    {
        return $this->hasOne(PesanSaranDetail::class, 'pesan_saran_id')->latestOfMany();
    }
}
