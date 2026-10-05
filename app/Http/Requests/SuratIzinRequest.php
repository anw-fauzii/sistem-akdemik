<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratIzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan ini true agar request tidak ditolak (403)
    }

    public function rules(): array
    {
        return [
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jenis' => 'required|in:sakit,izin,lainnya',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * INI YANG PENTING: 
     * Jika method ini tidak ada, Laravel akan mencari ke folder lang/en/validation.php.
     * Jika folder lang tidak ada, maka munculnya teks mentah 'validation.required'.
     */
    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Waduh, tanggal mulainya jangan lupa diisi ya!',
            'tanggal_selesai.required' => 'Waduh, tanggal selesainya jangan lupa diisi ya!',
            'tanggal_mulai.date'     => 'Format tanggal mulainya salah nih.',
            'tanggal_selesai.date'   => 'Format tanggal selesainya salah nih.',
            'jenis.required'   => 'Pilih dulu jenis izinnya: Sakit atau Izin.',
            'jenis.in'         => 'Pilihan jenis izin tidak valid.',
            'file.mimes' => 'File harus berupa gambar (jpg, png) atau PDF.',
            'file.max'   => 'Filenya melebihi ukuran maksimal, maksimal 2MB.',
        ];
    }
}   