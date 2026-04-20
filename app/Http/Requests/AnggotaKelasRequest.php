<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnggotaKelasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'siswa_nis'   => 'required|array',
            'siswa_nis.*' => 'exists:siswa,nis',
            'kelas_id'    => 'required|exists:kelas,id',
            'pendaftaran' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_nis.required' => 'Pilih minimal satu siswa.',
            'siswa_nis.*.exists' => 'Salah satu siswa tidak terdaftar.',
            'kelas_id.exists'    => 'Kelas tidak valid.',
        ];
    }
}