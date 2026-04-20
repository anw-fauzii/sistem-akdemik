<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriAdministrasiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|string|max:255',
            'jenis'         => 'required|string',
            'semester'      => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'jenis.required'         => 'Jenis kategori wajib diisi.',
            'semester.required'      => 'Pilihan Semester wajib diisi.',
        ];
    }
}