<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKesehatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumsi otorisasi dihandle Middleware
    }

    public function rules(): array
    {
        return [
            'bulan_spp_id'       => 'required|exists:bulan_spp,id',
            'anggota_kelas_id'   => 'required|array',
            'anggota_kelas_id.*' => 'exists:anggota_kelas,id',
            'tb'      => 'nullable|array',
            'bb'      => 'nullable|array',
            'lila'    => 'nullable|array',
            'lika'    => 'nullable|array',
            'lp'      => 'nullable|array',
            'mata'    => 'nullable|array',
            'telinga' => 'nullable|array',
            'gigi'    => 'nullable|array',
            'hasil'   => 'nullable|array',
            'tensi'   => 'nullable|array',
        ];
    }
}