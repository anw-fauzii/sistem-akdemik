<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Helper global untuk ambil user yang sedang login
 *
 * @return User|null
 */
function user(): ?User
{
    return Auth::user();
}
