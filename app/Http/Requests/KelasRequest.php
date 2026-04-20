<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'guru_nipy'       => 'required|exists:guru,nipy',
            'pendamping_nipy' => 'required|exists:guru,nipy',
            'tingkatan_kelas' => 'required|string',
            'nama_kelas'      => 'required|string|max:100',
            'romawi'          => 'required|string',
            'jenjang'         => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'guru_nipy.required'       => 'Wali kelas wajib dipilih.',
            'guru_nipy.exists'         => 'Guru yang dipilih tidak terdaftar di sistem.',
            
            'pendamping_nipy.required' => 'Guru pendamping wajib dipilih.',
            'pendamping_nipy.exists'   => 'Guru pendamping yang dipilih tidak terdaftar.',
            
            'tingkatan_kelas.required' => 'Tingkatan kelas (misal: 1, 2, 3) wajib diisi.',
            
            'nama_kelas.required'      => 'Nama kelas wajib diisi.',
            'nama_kelas.max'           => 'Nama kelas terlalu panjang (maksimal 100 karakter).',
            
            'romawi.required'          => 'Format romawi wajib diisi.',
            
            'jenjang.required'         => 'Jenjang pendidikan (misal: TK, SD) wajib diisi.',
        ];
    }
}