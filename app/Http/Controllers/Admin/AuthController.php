<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $user = Auth::user();

        if (! in_array($user->role, ['admin', 'seller'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'You do not have access to the admin portal.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route($user->role === 'admin' ? 'admin.dashboard' : 'seller.dashboard'));
    }
}
