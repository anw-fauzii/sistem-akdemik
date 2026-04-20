<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaJemputanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi (admin) ditangani oleh Middleware di routes/web.php
    }

    public function rules(): array
    {
        return [
            // Validasi memastikan input adalah array dan datanya ada di tabel
            'anggota_kelas_ids'   => 'required|array|min:1',
            'anggota_kelas_ids.*' => 'exists:anggota_kelas,id',
            'jemputan_id'         => 'required|exists:jemputan,id',
            'keterangan'          => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'anggota_kelas_ids.required' => 'Tidak ada siswa yang dipilih.',
        ];
    }
}