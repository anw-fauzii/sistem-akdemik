<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestasiSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'anggota_kelas_id'   => 'required|array',
            'anggota_kelas_id.*' => 'exists:anggota_kelas,id',
            'nama_prestasi'      => 'required|string|max:255',
            'kategori'           => 'required|in:akademik,non_akademik',
            'tingkat'            => 'required|string|max:100',
            'peringkat'          => 'required|string|max:50',
            'tanggal'            => 'required|date',
            'file_sertifikat'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'         => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            // Field: anggota_kelas_id (Array Siswa)
            'anggota_kelas_id.required' => 'Silakan pilih minimal satu siswa yang meraih prestasi ini.',
            'anggota_kelas_id.array'    => 'Data siswa harus berupa format array.',
            'anggota_kelas_id.*.exists' => 'Data siswa yang dipilih tidak ditemukan dalam sistem.',

            // Field: nama_prestasi
            'nama_prestasi.required'    => 'Nama prestasi atau judul lomba wajib diisi.',
            'nama_prestasi.string'      => 'Nama prestasi harus berupa teks.',
            'nama_prestasi.max'         => 'Nama prestasi tidak boleh lebih dari 255 karakter.',

            // Field: kategori
            'kategori.required'         => 'Pilih kategori prestasi (Akademik atau Non-Akademik).',
            'kategori.in'               => 'Kategori yang dipilih tidak valid.',

            // Field: tingkat
            'tingkat.required'          => 'Tingkat prestasi (misal: Kabupaten, Provinsi, Nasional) wajib diisi.',
            'tingkat.max'               => 'Input tingkat prestasi terlalu panjang (maksimal 100 karakter).',

            // Field: peringkat
            'peringkat.required'        => 'Peringkat atau Juara (misal: Juara 1, Harapan 2) wajib diisi.',
            'peringkat.max'             => 'Input peringkat terlalu panjang (maksimal 50 karakter).',

            // Field: tanggal
            'tanggal.required'          => 'Tanggal perolehan prestasi wajib diisi.',
            'tanggal.date'              => 'Format tanggal tidak valid.',

            // Field: file_sertifikat
            'file_sertifikat.file'      => 'Sertifikat harus berupa file.',
            'file_sertifikat.mimes'     => 'Format sertifikat hanya diperbolehkan: JPG, JPEG, PNG, atau PDF.',
            'file_sertifikat.max'       => 'Ukuran file sertifikat maksimal adalah 2MB.',

            'keterangan.string'         => 'Keterangan harus berupa teks.',
        ];
    }
}