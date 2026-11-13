<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau kata sandi yang Anda masukkan salah.',
                    'errors' => [
                        'email' => ['Email atau kata sandi yang Anda masukkan salah.'],
                    ],
                ], 422);
            }

            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->role;
        $request->session()->put('role', $role);

        if ($role === 'admin') {
            $intended = $request->session()->pull('url.intended');
            $redirectUrl = ($intended && str_contains($intended, '/admin')) ? $intended : route('admin.dashboard');

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil masuk! Mengalihkan ke dashboard admin...',
                    'role' => 'admin',
                    'redirect' => $redirectUrl,
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        $redirectUrl = route('main.dashboard');

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil masuk! Selamat datang kembali.',
                'role' => $role,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->intended($redirectUrl);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar. Silakan login kembali untuk mengakses dashboard.');
    }
}
