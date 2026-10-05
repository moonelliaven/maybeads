<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
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

            // Deteksi otomatis email hlccntayuni@gmail.com sebagai admin
            if (strtolower($user->email) === 'hlccntayuni@gmail.com' && $user->role !== 'admin') {
                $user->role = 'admin';
                $user->save();
            }

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

        $isAdmin = (strtolower($validated['email']) === 'hlccntayuni@gmail.com');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $isAdmin ? 'admin' : 'user',
        ]);

        Auth::login($user);

        $request->session()->regenerate();
        $request->session()->put('role', $user->role);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran akun berhasil! Selamat datang di Maybeads.',
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('home'),
            ]);
        }

        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'home');
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and create/login session.
     */
    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('login')->with('error', 'Proses masuk dengan Google dibatalkan.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal terhubung dengan akun Google: ' . $e->getMessage());
        }

        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->with('error', 'Email tidak ditemukan dari akun Google Anda.');
        }

        // Deteksi email khusus sebagai Admin
        $isAdmin = (strtolower($email) === 'hlccntayuni@gmail.com');

        // Cari user yang sudah terdaftar berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            // Update data Google ID & avatar jika ada
            $user->google_id = $googleUser->getId();
            if ($googleUser->getAvatar()) {
                $user->avatar = $googleUser->getAvatar();
            }
            if ($isAdmin) {
                $user->role = 'admin';
            }
            $user->save();
        } else {
            // Buat akun baru berdasarkan info dari Google
            $user = User::create([
                'name' => $googleUser->getName() ?: explode('@', $email)[0],
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => $isAdmin ? 'admin' : 'user',
                'password' => Hash::make(Str::random(32)),
            ]);
        }

        // Login ke session
        Auth::login($user, true);

        $request->session()->regenerate();
        $request->session()->put('role', $user->role);

        // Jika admin, arahkan ke dashboard admin
        if ($user->isAdmin()) {
            $intended = $request->session()->pull('url.intended');
            $redirectUrl = ($intended && str_contains($intended, '/admin')) ? $intended : route('admin.dashboard');
            return redirect($redirectUrl)->with('success', 'Selamat datang kembali, Administrator!');
        }

        // Jika user biasa
        $intended = $request->session()->pull('url.intended');
        $redirectUrl = ($intended && !str_contains($intended, '/admin') && !str_contains($intended, '/login')) ? $intended : route('home');

        return redirect($redirectUrl)->with('success', 'Berhasil masuk dengan akun Google!');
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
