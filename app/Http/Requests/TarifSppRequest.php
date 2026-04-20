<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TarifSppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi ditangani Middleware Route
    }

    /**
     * Membersihkan format mata uang (menghilangkan titik) sebelum divalidasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'spp'         => $this->spp ? (int) str_replace('.', '', $this->spp) : null,
            'biaya_makan' => $this->biaya_makan ? (int) str_replace('.', '', $this->biaya_makan) : null,
            'snack'       => $this->snack ? (int) str_replace('.', '', $this->snack) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'unit'        => 'required|string|max:100',
            'tahun_masuk' => 'required|numeric|digits:4',
            'spp'         => 'required|numeric|min:0',
            'biaya_makan' => 'required|numeric|min:0',
            'snack'       => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'unit.required'        => 'Unit pendidikan (misal: SD, SMP) wajib diisi.',
            'unit.string'          => 'Unit pendidikan harus berupa teks.',
            'unit.max'             => 'Nama unit tidak boleh lebih dari 100 karakter.',

            'tahun_masuk.required' => 'Tahun masuk wajib diisi.',
            'tahun_masuk.numeric'  => 'Tahun masuk harus berupa angka.',
            'tahun_masuk.digits'   => 'Tahun masuk harus terdiri dari 4 digit angka (contoh: 2024).',

            'spp.required'         => 'Nominal SPP wajib diisi.',
            'spp.numeric'          => 'Nominal SPP harus berupa angka yang valid.',
            'spp.min'              => 'Nominal SPP tidak boleh bernilai negatif.',

            'biaya_makan.required' => 'Biaya makan wajib diisi (isi 0 jika tidak ada).',
            'biaya_makan.numeric'  => 'Biaya makan harus berupa angka yang valid.',
            'biaya_makan.min'      => 'Biaya makan tidak boleh bernilai negatif.',

            'snack.required'       => 'Biaya snack wajib diisi (isi 0 jika tidak ada).',
            'snack.numeric'        => 'Biaya snack harus berupa angka yang valid.',
            'snack.min'            => 'Biaya snack tidak boleh bernilai negatif.',
        ];
    }
}