<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelas extends Model
{
    use HasFactory;
    protected $table = 'anggota_kelas';
    protected $fillable = [
        'siswa_nis',
        'kelas_id',
        'pendaftaran',
    ];

    public function scopeTahunAjaranAktif($query)
    {
        $tahunAjaran = TahunAjaran::latest()->first();

        return $query->whereHas('kelas', function ($q) use ($tahunAjaran) {
            $q->where('tahun_ajaran_id', $tahunAjaran->id);
        });
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nis', 'nis');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pembayaranSPP()
    {
        return $this->hasMany(PembayaranSpp::class, 'anggota_kelas_id');
    }
    
    public function ekstrakurikuler()
    {
        return $this->hasMany(AnggotaEkstrakurikuler::class)->with('ekstrakurikuler');
    }

    public function anggotaEkstrakurikuler()
    {
        return $this->hasOne(AnggotaEkstrakurikuler::class);
    }

    public function anggotaJemputan()
    {
        return $this->hasOne(AnggotaJemputan::class);
    }

    public function pembayaranTagihanTahunan()
    {
        return $this->hasMany(PembayaranTagihanTahunan::class, 'anggota_kelas_id');
    }

    public function anggotaT2q()
    {
        return $this->hasOne(AnggotaT2Q::class);
    }

    public function jemputan()
    {
        return $this->hasOne(AnggotaJemputan::class);
    }

    public function dataKesehatan()
    {
        return $this->hasOne(Kesehatan::class);
    }

    public function prestasi()
    {
        return $this->belongsToMany(
            PrestasiSiswa::class,
            'prestasi_siswa_anggota',
            'anggota_kelas_id',
            'prestasi_siswa_id'
        );
    }
}
