<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JemputanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi ditangani Middleware
    }

    public function rules(): array
    {
        return [
            'driver'         => 'required|string|max:255',
            'harga_pp'       => 'required|numeric|min:0',
            'harga_setengah' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'driver.required'         => 'Nama Driver wajib diisi.',
            'harga_pp.required'       => 'Harga Pulang Pergi wajib diisi.',
            'harga_setengah.required' => 'Harga Setengah wajib diisi.',
            'numeric'                 => 'Input :attribute harus berupa angka.',
        ];
    }
}