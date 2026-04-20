<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulanSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_bulan'  => 'required|string|max:50',
            'bulan_angka' => 'required',
            'tambahan'    => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_bulan.required'  => 'Nama bulan SPP wajib diisi.',
            'bulan_angka.required' => 'Urutan bulan (angka) wajib diisi.',
            'tambahan.numeric'     => 'Jumlah tambahan harus berupa angka.',
        ];
    }
}