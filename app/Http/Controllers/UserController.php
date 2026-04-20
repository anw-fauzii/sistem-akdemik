<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Services\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserProfileService $profileService
    ) {}

    public function profil(): View
    {
        $data = $this->profileService->getSiswaProfile(Auth::user()->email);
        
        return view('user.index', compact('data'));
    }

    public function password(): View
    {
        $user = Auth::user();
        return view('user.password', compact('user'));
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $this->profileService->updatePassword($user, $request->new_password);
        return redirect()->back()->with('success', 'Password berhasil diperbarui.');
    }
}