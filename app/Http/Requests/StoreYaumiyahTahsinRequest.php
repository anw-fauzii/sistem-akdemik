<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreYaumiyahTahsinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'records'                    => ['required', 'array', 'min:1'],
            'records.*.anggota_t2q_id'   => ['required', 'exists:anggota_t2q,id'],
            'records.*.tanggal'          => ['required', 'date'],
            'records.*.daftar_jilid_id'  => ['nullable', 'exists:daftar_jilid,id'],
            'records.*.surah_alquran_id' => ['nullable', 'exists:surah_alquran,id'],
            'records.*.angka_arab_id'    => ['nullable', 'exists:angka_arab,id'],
            'records.*.nilai'            => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }
}