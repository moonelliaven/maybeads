<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            // Simpan role ke dalam session
            $request->session()->put('role', $user->role);

            // Arahkan admin ke dashboard atau halaman admin yang dituju
            if ($user->isAdmin()) {
                $intended = $request->session()->pull('url.intended');
                $redirectUrl = ($intended && str_contains($intended, '/admin')) ? $intended : route('admin.dashboard');

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Berhasil masuk! Mengalihkan ke dashboard admin...',
                        'role' => $user->role,
                        'redirect' => $redirectUrl,
                    ]);
                }

                return redirect($redirectUrl);
            }

            // User biasa diarahkan ke URL sebelumnya (non-admin) atau ke beranda
            $intended = $request->session()->pull('url.intended');
            $redirectUrl = ($intended && !str_contains($intended, '/admin') && !str_contains($intended, '/login')) ? $intended : route('home');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil masuk! Selamat datang kembali.',
                    'role' => $user->role,
                    'redirect' => $redirectUrl,
                ]);
            }

            return redirect($redirectUrl);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi yang Anda masukkan salah.',
                'errors' => [
                    'email' => [trans('auth.failed')],
                ],
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Show the register form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Handle new user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        $request->session()->regenerate();
        $request->session()->put('role', $user->role);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran akun berhasil! Selamat datang di Maybeads.',
                'redirect' => route('home'),
            ]);
        }

        return redirect()->route('home');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
