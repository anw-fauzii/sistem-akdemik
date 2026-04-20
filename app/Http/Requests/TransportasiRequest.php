<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransportasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $transportasiId = $this->route('transportasi');

        return [
            'nama_transportasi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('transportasi', 'nama_transportasi')->ignore($transportasiId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_transportasi.required' => 'Nama transportasi wajib diisi.',
            'nama_transportasi.unique'   => 'Jenis transportasi ini sudah terdaftar.',
        ];
    }
}