<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministrasiGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi ditangani Middleware Controller
    }

    public function rules(): array
    {
        return [
            'kategori_administrasi_id' => 'required|exists:kategori_administrasi,id',
            'semester'                 => 'nullable|numeric|in:1,2',
            'file'                     => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
        ];
    }
}