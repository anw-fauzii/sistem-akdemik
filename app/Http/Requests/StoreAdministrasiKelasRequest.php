<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministrasiKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'kategori_administrasi_id' => 'required|exists:kategori_administrasi,id',
            'semester'                 => 'nullable|numeric|in:1,2',
            'files'                    => 'required|array|min:1',
            'files.*'                  => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx', 
        ];
    }
}