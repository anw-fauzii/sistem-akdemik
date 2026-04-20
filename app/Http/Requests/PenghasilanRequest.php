<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenghasilanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $penghasilanId = $this->route('penghasilan');

        return [
            'nama_penghasilan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('penghasilan', 'nama_penghasilan')->ignore($penghasilanId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_penghasilan.required' => 'Nama penghasilan wajib diisi.',
            'nama_penghasilan.unique'   => 'Rentang penghasilan ini sudah ada.',
        ];
    }
}