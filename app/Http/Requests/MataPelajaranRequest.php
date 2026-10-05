<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MataPelajaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool 
    { 
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $mataPelajaran = $this->route('daftar_mata_pelajaran');
        $mapelId = $mataPelajaran ? $mataPelajaran->id : null;

        return [
            'kategori_mata_pelajaran_id' => [
                'required',
                'integer',
                // Memastikan ID ada di tabel kategori_mata_pelajaran
                'exists:kategori_mata_pelajaran,id',
            ],
            'nama_mapel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mata_pelajaran', 'nama_mapel')->ignore($mapelId),
            ],
            'ringkasan_mapel' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_mata_pelajaran_id.required' => 'Kategori mata pelajaran wajib dipilih.',
            'kategori_mata_pelajaran_id.exists'   => 'Kategori mata pelajaran yang dipilih tidak valid.',
            
            'nama_mapel.required'                 => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.max'                      => 'Nama mata pelajaran tidak boleh lebih dari 255 karakter.',
            'nama_mapel.unique'                   => 'Nama mata pelajaran ini sudah ada, gunakan nama lain.',
            
            'ringkasan_mapel.max'                 => 'Ringkasan mata pelajaran tidak boleh lebih dari 255 karakter.',
        ];
    }
}