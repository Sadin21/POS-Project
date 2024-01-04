<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function authenticate(Request $request): View | RedirectResponse
    {
        if ($request->getMethod() === 'GET') {
            return view('pages.auth.login');
        }

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

    public function reset(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'nip' => 'required|exists:users,nip',
                'phone' => 'required|exists:users,phone',
            ]);
    
            $nip = $request->nip;
    
            $newPassword = Str::random(10);
            $user = User::find($nip);
            if (!$user) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }

            $user->password = bcrypt($newPassword);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset',
                'data' => $newPassword,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Password gagal direset'], 500);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'Logout success');
    }
}
