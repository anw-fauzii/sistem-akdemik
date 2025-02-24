<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswa';
    protected $fillable = [
        'nis',
        'kelas_id',
        'guru_nipy',
        'jenis_pendaftaran',
        'nama_lengkap',
        'jenis_kelamin',
        'nisn',
        'nik',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'akta_lahir',
        'agama',
        'kewarganegaraan',
        'nama_negara',
        'berkebutuhan_khusus_id',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'lintang',
        'bujur',
        'tempat_tinggal',
        'transportasi_id',
        'anak_ke',
        'jumlah_saudara',
        
        'nik_ayah',
        'nama_ayah', 
        'lahir_ayah',
        'jenjang_pendidikan_ayah_id', 
        'pekerjaan_ayah_id',
        'penghasilan_ayah_id',
        'berkebutuhan_khusus_ayah_id',

        'nik_ibu',
        'nama_ibu', 
        'lahir_ibu',
        'jenjang_pendidikan_ibu_id',
        'pekerjaan_ibu_id',
        'penghasilan_ibu_id',
        'berkebutuhan_khusus_ibu_id',

        'nik_wali',
        'nama_wali', 
        'lahir_wali',
        'jenjang_pendidikan_wali_id',
        'pekerjaan_wali_id',
        'penghasilan_wali_id',
        'berkebutuhan_khusus_wali_id',

        'nomor_hp',
        'whatsapp',
        'email',

        'tinggi_badan',
        'berat_badan',
        'jarak',
        'lingkar_kepala',
        'lingkar',

        'avatar',
        'status',
    ];

    protected $dates= ['tanggal_lahir'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'nis', 'email');
    }
}
