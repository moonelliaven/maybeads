<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->has('redirect')) {
            $redirect = $request->query('redirect');
            if ($redirect && !str_contains($redirect, '/login') && !str_contains($redirect, '/logout')) {
                session()->put('url.intended', $redirect);
            }
        }
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

        $intended = $request->session()->pull('url.intended');
        $redirectUrl = $intended ?: route('main.dashboard');

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
        // 1. Dapatkan URL asal sebelum sesi di-invalidate
        $referer = $request->input('redirect_to')
            ?? $request->headers->get('referer')
            ?? url()->previous();

        // 2. Lakukan proses logout & bersihkan sesi
        Auth::logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // 3. Evaluasi path asal
        $path = '/';
        if ($referer) {
            $parsed = parse_url($referer, PHP_URL_PATH);
            if ($parsed) {
                $path = '/' . ltrim($parsed, '/');
            }
        }

        // Tentukan apakah user logout dari dashboard (Admin Dashboard atau User Dashboard)
        $isFromDashboard = (
            str_starts_with($path, '/admin') ||
            str_contains($path, '/dashboard') ||
            str_contains($path, '/orders') ||
            $path === '/dashboard' ||
            $path === '/main/dashboard' ||
            $path === '/admin'
        );

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Anda telah berhasil keluar.',
                'redirect' => $isFromDashboard ? route('login') : ($referer && !str_contains($path, '/logout') ? $referer : route('home')),
            ]);
        }

        // Jika awalnya dari Dashboard: Arahkan ke halaman login
        if ($isFromDashboard) {
            return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari dashboard. Silakan login kembali.');
        }

        // Jika dari Halaman Utama, Landing Page, Kontak, atau halaman toko lainnya:
        // Arahkan kembali ke tempat yang sama
        if ($referer && !str_contains($path, '/logout') && !str_contains($path, '/login')) {
            return redirect()->to($referer)->with('success', 'Anda telah berhasil keluar.');
        }

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
