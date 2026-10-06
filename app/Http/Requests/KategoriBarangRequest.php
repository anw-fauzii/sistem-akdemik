<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriBarangRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id' => 'required|string|max:255',
            'nama_kategori_barang' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.string' => 'ID harus berupa string.',
            'nama_kategori_barang.required' => 'Nama kategori wajib diisi.',
        ];
    }
}