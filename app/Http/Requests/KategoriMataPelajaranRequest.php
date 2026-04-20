<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriMataPelajaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $kategoriId = $this->route('kategori_mata_pelajaran');

        return [
            'kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori_mata_pelajaran', 'kategori')->ignore($kategoriId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.required' => 'Nama kategori wajib diisi.',
            'kategori.unique'   => 'Nama kategori ini sudah ada, gunakan nama lain.',
        ];
    }
}