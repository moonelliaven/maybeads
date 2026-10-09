<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // path view
    public function showLogin()
    {
        return view('auth.login');
    }

    // login request
    public function login(Request $request)
    {
        // user validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // memeriksa login     
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau kata sandi yang Anda masukkan salah.',
                    'errors' => ['email' => ['Email atau kata sandi yang Anda masukkan salah.']]
                ], 422);
            }
            return back()->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $redirect = $user->role === 'admin' ? '/admin' : '/';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'role' => $user->role,
                'redirect' => $redirect,
                'message' => 'Berhasil masuk!',
            ]);
        }

        return redirect()->intended($redirect);
    }

    // logout request
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect' => '/']);
        }

        return redirect('/');
    }
}