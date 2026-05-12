<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TargetCapaianTahsinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool 
    { 
        return true; // Pastikan ini true agar form bisa disubmit
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat'         => 'required|string|max:10',
            'daftar_jilid_id' => 'required|exists:daftar_jilid,id',
            'surah_alquran_id' => 'required|exists:surah_alquran,id',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'tahun_ajaran_id.exists'   => 'Tahun Ajaran yang dipilih tidak valid di database.',
            
            'tingkat.required'         => 'Tingkat kelas wajib diisi (misal: 1, 2, PG, A).',
            'tingkat.string'           => 'Format tingkat kelas tidak valid.',
            'tingkat.max'              => 'Tingkat kelas maksimal 10 karakter.',
            
            'daftar_jilid_id.required' => 'Target Jilid wajib dipilih.',
            'daftar_jilid_id.exists'   => 'Jilid yang dipilih tidak ditemukan di database.',
            'surah_alquran_id.required' => 'Surah Al-Quran wajib dipilih.',
            'surah_alquran_id.exists'   => 'Surah Al-Quran yang dipilih tidak valid di database.',
        ];
    }
}