<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagihanTahunanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Proteksi otorisasi dipindahkan ke Route Middleware
    }

    public function rules(): array
    {
        return [
            'jenjang' => 'required|string|max:50',
            'jumlah'  => 'required|numeric|min:0',
            'jenis'   => 'required|string|max:100',
            'kelas'   => 'nullable|string|max:50', // Kolom ini sekarang tervalidasi dan aman
        ];
    }

    public function messages(): array
    {
        return [
            'jenjang.required' => 'Jenjang pendidikan wajib diisi.',
            'jumlah.required'  => 'Jumlah Biaya wajib diisi.',
            'jumlah.numeric'   => 'Jumlah harus berupa angka.',
            'jumlah.min'       => 'Jumlah biaya tidak boleh negatif.',
            'jenis.required'   => 'Jenis pembayaran wajib diisi.',
        ];
    }
}