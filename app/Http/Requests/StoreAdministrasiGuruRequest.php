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
            // PENTING: Ubah name di HTML Anda dari 'judul' ke 'kategori_administrasi_id'
            'kategori_administrasi_id' => 'required|exists:kategori_administrasi,id',
            'semester'                 => 'nullable|numeric|in:1,2',
            // Ubah name di HTML dari 'link[]' menjadi 'files[]'
            'files'                    => 'required|array|min:1',
            // Validasi ketat: Maks 10MB, hanya dokumen PDF/Word/Excel
            'files.*'                  => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx', 
        ];
    }
}