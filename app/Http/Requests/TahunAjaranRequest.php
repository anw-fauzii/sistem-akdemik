<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TahunAjaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_tahun_ajaran' => 'required|string|max:255',
            'semester'          => 'required|string|in:1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_tahun_ajaran.required' => 'Nama tahun ajaran wajib diisi.',
            'semester.required'          => 'Semester wajib diisi.',
        ];
    }
}