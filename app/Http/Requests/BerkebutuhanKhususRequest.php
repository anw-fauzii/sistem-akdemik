<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BerkebutuhanKhususRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $id = $this->route('kategori_kebutuhan');

        return [
            'nama_berkebutuhan_khusus' => [
                'required',
                'string',
                'max:255',
                Rule::unique('berkebutuhan_khusus', 'nama_berkebutuhan_khusus')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_berkebutuhan_khusus.required' => 'Nama kebutuhan khusus wajib diisi.',
            'nama_berkebutuhan_khusus.unique'   => 'Data ini sudah ada dalam sistem.',
        ];
    }
}