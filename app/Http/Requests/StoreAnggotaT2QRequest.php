<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaT2QRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumsi Middleware sudah menangani role admin
    }

    public function rules(): array
    {
        return [
            // PERBAIKAN NAMA: Ubah name di HTML Anda dari siswa_nis[] menjadi anggota_kelas_ids[]
            'anggota_kelas_ids'   => 'required|array|min:1',
            'anggota_kelas_ids.*' => 'exists:anggota_kelas,id',
            'guru_nipy'           => 'required|exists:guru,nipy',
            'tingkat'             => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'anggota_kelas_ids.required' => 'Tidak ada siswa yang dipilih.',
        ];
    }
}