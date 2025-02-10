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
        'tanggal_lahir',
        'nuptk',
        'alamat',
        'avatar',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'nipy', 'email');
    }
}
