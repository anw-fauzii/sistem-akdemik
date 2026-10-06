<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LokasiBarangRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'nama_lokasi_barang' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.string' => 'ID harus berupa string.',
            'nama_lokasi_barang.required' => 'Nama lokasi wajib diisi.',
            'unit.required' => 'Unit wajib diisi.',
        ];
    }
}