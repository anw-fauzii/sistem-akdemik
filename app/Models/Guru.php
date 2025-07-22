<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $primaryKey = 'nipy';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'guru';
    protected $fillable = [
        'nama_lengkap',
        'gelar',
        'jabatan',
        'nipy',
        'telepon',
        'jenis_kelamin',
        'tempat_lahir',
        'nuptk',
        'alamat',
        'avatar',
        'status',
        'tanggal_lahir'
    ];
    protected $dates = ['tanggal_lahir'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'nipy', 'email');
    }

    public function anggotaT2q()
    {
        return $this->hasMany(AnggotaT2Q::class);
    }
}
