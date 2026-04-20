<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePresensiEkskulRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'tanggal'      => 'required|date',
            'presensi'     => 'required|array|min:1',
            'presensi.*'   => 'required|in:hadir,sakit,izin,alpa', // Sesuaikan dengan enum status Anda
        ];
    }
}