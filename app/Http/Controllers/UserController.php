<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profil()
    {
        $user = User::findOrFail(Auth::user()->id);
        return view('user.index', compact('user'));
    }

    public function password()
    {
        $user = User::findOrFail(Auth::user()->id);
        return view('user.password', compact('user'));
    }

    public function update(Request $request)
    {
        if (user()?->hasAnyRole('admin|siswa')){
            $request->validate([
                'old_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                'confirm_password' => ['same:new_password']
            ], [
                'old_password.required' => 'Password lama wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'confirm_password.required' => 'Konfirmasi password tidak sama dengan yang baru.',
            ]);
            $user = User::findOrFail(Auth::user()->id);
            $user->password = Hash::make($request->new_password);
            $user->save();
            Auth::login($user);
            return redirect()->back()->with('success','Password Berhasil Dipdate');
        }
        else{
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

}
