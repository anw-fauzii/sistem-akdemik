<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKedisiplinanSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'anggota_kelas_id'     => ['required', 'integer', 'exists:anggota_kelas,id'],
            'kedisiplinan_poin_id' => ['required', 'integer', 'exists:kedisiplinan_poin,id'],
            'tanggal_kejadian'     => ['required', 'date', 'before_or_equal:today'],
            'keterangan'           => ['nullable', 'string', 'max:500'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'anggota_kelas_id.required' => 'Siswa harus dipilih.',
            'kedisiplinan_poin_id.required' => 'Aturan kedisiplinan harus dipilih.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
        ];
    }
}