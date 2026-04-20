<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePresensiRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani oleh Middleware di routes
    }

    /**
     * Aturan validasi yang diterapkan ke request.
     */
    public function rules(): array
    {
        return [
            'tanggal'    => 'required|date',
            'presensi'   => 'required|array',
            // Memastikan status hanya menerima 4 opsi standar untuk mencegah data kotor
            'presensi.*' => 'nullable|string|in:Hadir,Sakit,Izin,Alpa',
            'waktu'      => 'nullable|array',
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            // Field: Tanggal
            'tanggal.required'  => 'Tanggal presensi wajib diisi.',
            'tanggal.date'      => 'Format tanggal presensi tidak valid.',

            // Field: Presensi (Array)
            'presensi.required' => 'Data presensi siswa wajib diisi minimal satu.',
            'presensi.array'    => 'Format data presensi tidak valid (harus berupa daftar/array).',
            
            // Field: Item dalam Presensi
            'presensi.*.in'     => 'Status presensi yang dipilih tidak valid. Pilihan yang diizinkan hanya: Hadir, Sakit, Izin, atau Alpa.',
            'presensi.*.string' => 'Status presensi harus berupa teks.',

            // Field: Waktu
            'waktu.array'       => 'Format input waktu kedatangan tidak valid.',
        ];
    }
}