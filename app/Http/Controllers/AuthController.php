<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request): View | RedirectResponse {
        if ($request->getMethod() === 'GET') return view('pages.auth.login');

        $credentials = $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required',
            ]);

        if (Auth::attempt($credentials)) {
            // dd(Auth::user()->nip);
            return redirect()->route('sale.index')->with('success', 'Login success');
        } else {
            return redirect()->back()->with('error', 'Login gagal');
        }
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Logout success');
    }
}
