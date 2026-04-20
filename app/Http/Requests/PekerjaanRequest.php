<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PekerjaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pekerjaanId = $this->route('pekerjaan');

        return [
            'nama_pekerjaan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pekerjaan', 'nama_pekerjaan')->ignore($pekerjaanId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pekerjaan.required' => 'Nama Pekerjaan wajib diisi.',
            'nama_pekerjaan.unique'   => 'Nama pekerjaan ini sudah ada dalam sistem.',
        ];
    }
}