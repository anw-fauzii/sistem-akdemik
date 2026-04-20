<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Proteksi role sudah ditangani oleh Middleware
    }

    public function rules(): array
    {
        return [
            'kegiatan' => 'required|string|max:255',
            'tanggal'  => 'required|date',
            'unit'     => 'required|string',
            'jam'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'kegiatan.required' => 'Nama agenda wajib diisi.',
            'tanggal.required'  => 'Tanggal wajib diisi.',
            'unit.required'     => 'Unit wajib diisi.',
        ];
    }
}