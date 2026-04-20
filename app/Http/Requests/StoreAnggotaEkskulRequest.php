<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaEkskulRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pengecekan role akan di-handle oleh Middleware
    }

    public function rules(): array
    {
        return [
            // PENTING: Pastikan name di tag HTML <select> Anda diubah menjadi anggota_kelas_ids[]
            'anggota_kelas_ids'   => 'required|array|min:1',
            'anggota_kelas_ids.*' => 'exists:anggota_kelas,id',
            'ekstrakurikuler_id'  => 'required|exists:ekstrakurikuler,id',
        ];
    }

    public function messages(): array
    {
        return [
            'anggota_kelas_ids.required' => 'Tidak ada siswa yang dipilih dari kotak.',
        ];
    }
}