<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenjangPendidikanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $jenjangId = $this->route('jenjang_pendidikan');

        return [
            'nama_jenjang_pendidikan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenjang_pendidikan', 'nama_jenjang_pendidikan')->ignore($jenjangId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jenjang_pendidikan.required' => 'Nama pendidikan wajib diisi.',
            'nama_jenjang_pendidikan.unique'   => 'Jenjang pendidikan ini sudah terdaftar.',
        ];
    }
}