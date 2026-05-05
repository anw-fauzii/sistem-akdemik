<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKedisiplinanPoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $id = $this->route('kedisiplinan_poin');
        return [
            'nama_aturan' => ['required', 'string', 'max:150', Rule::unique('kedisiplinan_poin', 'nama_aturan')->ignore($id)],
            'kategori'    => ['required', 'in:pelanggaran,prestasi'],
            'tingkat'     => ['required', 'in:ringan,sedang,berat'],
            'poin'        => ['required', 'integer', 'not_in:0'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'nama_aturan.unique' => 'Nama aturan ini sudah terdaftar di sistem.',
            'nama_aturan.required' => 'Nama aturan wajib diisi.',
            'poin.not_in' => 'Poin kedisiplinan tidak boleh bernilai 0.',
            'poin.required' => 'Poin wajib diisi.',
            'tingkat.required' => 'Tingkat (ringan/sedang/berat) wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
        ];
    }
}
