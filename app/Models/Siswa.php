<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'siswa';
    protected $fillable = [
        'nis',
        'kelas_id',
        'guru_nipy',
        'ekstrakurikuler_id',
        'jemputan_id',
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
        'waktu_tempuh',

        'tarif_spp_id',
        'avatar',
        'status',
    ];

    protected $dates= ['tanggal_lahir'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'nis', 'email');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jemputan()
    {
        return $this->belongsTo(Jemputan::class);
    }

    public function pekerjaan_ayah()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ayah_id', 'id');
    }
    
    public function pekerjaan_ibu()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ibu_id', 'id');
    }

    public function pekerjaan_wali()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_wali_id', 'id');
    }

    public function penghasilan_ayah()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_ayah_id', 'id');
    }
    
    public function penghasilan_ibu()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_ibu_id', 'id');
    }

    public function penghasilan_wali()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_wali_id', 'id');
    }

    public function jenjang_pendidikan_ayah()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_ayah_id', 'id');
    }
    
    public function jenjang_pendidikan_ibu()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_ibu_id', 'id');
    }

    public function jenjang_pendidikan_wali()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_wali_id', 'id');
    }
    
    public function berkebutuhan_khusus_ayah()
    {
        return $this->belongsTo(BerkebutuhanKhusus::class, 'berkebutuhan_khusus_ayah_id', 'id');
    }
    
    public function berkebutuhan_khusus_ibu()
    {
        return $this->belongsTo(BerkebutuhanKhusus::class, 'berkebutuhan_khusus_ibu_id', 'id');
    }

    public function berkebutuhan_khusus_wali()
    {
        return $this->belongsTo(BerkebutuhanKhusus::class, 'berkebutuhan_khusus_wali_id', 'id');
    }
    
}
