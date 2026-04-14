<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanSaranDetail extends Model
{
    use HasFactory;

    protected $table = 'pesan_saran_detail';

    protected $fillable = [
        'pesan_saran_id',
        'sender',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function pesanSaran()
    {
        return $this->belongsTo(PesanSaran::class, 'pesan_saran_id');
    }
}
