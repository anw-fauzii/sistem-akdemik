<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchOldPassword;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi sudah di-handle oleh Middleware di routes/web.php
    }

    public function rules(): array
    {
        return [
            'old_password'     => ['required', new MatchOldPassword],
            'new_password'     => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:new_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required'     => 'Password lama wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal harus 8 karakter.',
            'confirm_password.required' => 'Konfirmasi password wajib diisi.',
            'confirm_password.same'     => 'Konfirmasi password tidak sama dengan password baru.',
        ];
    }
}