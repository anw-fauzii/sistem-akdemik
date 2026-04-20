<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EkstrakurikulerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani Middleware
    }

    public function rules(): array
    {
        return [
            'nama_ekstrakurikuler' => 'required|string|max:255',
            'guru_nipy'            => 'required|exists:guru,nipy',
            'biaya'                => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ekstrakurikuler.required' => 'Nama ekstrakurikuler wajib diisi.',
            'guru_nipy.required'            => 'Guru pembina wajib dipilih.', // TYPO DIPERBAIKI
            'guru_nipy.exists'              => 'Data guru tidak valid.',
            'biaya.numeric'                 => 'Jumlah biaya harus berupa angka yang valid.', 
        ];
    }
}