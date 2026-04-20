<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiSiswa extends Model
{
    use HasFactory;
    protected $table = 'prestasi_siswa';

    protected $fillable = [
        'nama_prestasi',
        'kategori',
        'tingkat',
        'peringkat',
        'penyelenggara',
        'tanggal',
        'keterangan',
        'file_sertifikat',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function anggotaKelas()
    {
        return $this->belongsToMany(
            AnggotaKelas::class,
            'prestasi_siswa_anggota',
            'prestasi_siswa_id',
            'anggota_kelas_id'
        );
    }

}
