<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Mail\VerificationCodeMail;

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
     * Handle initial registration and send email verification code.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        $email = strtolower(trim($validated['email']));

        // Generate 6-digit numeric OTP code
        $otp = (string) random_int(100000, 999999);

        // Simpan data pendaftaran sementara di Cache selama 15 menit
        $cacheKey = 'register_pending_' . md5($email);
        $pendingData = [
            'name' => trim($validated['name']),
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'otp' => $otp,
            'sent_at' => now()->timestamp,
        ];

        Cache::put($cacheKey, $pendingData, now()->addMinutes(15));

        // Kirim email verifikasi
        try {
            Mail::to($email)->send(new VerificationCodeMail($otp, $pendingData['name']));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirimkan email verifikasi. Pastikan konfigurasi email sudah benar atau coba beberapa saat lagi.',
                'error_detail' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'requires_verification' => true,
                'email' => $email,
                'message' => 'Kode verifikasi 6 digit telah dikirimkan ke email Anda (' . $email . '). Silakan cek kotak masuk atau folder spam.',
            ]);
        }

        return redirect()->route('register')->with('email_pending', $email);
    }

    /**
     * Validate verification code and finally register user into database.
     */
    public function verifyRegistration(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ], [
            'email.required' => 'Email tidak valid.',
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode verifikasi harus terdiri dari 6 digit.',
        ]);

        $email = strtolower(trim($validated['email']));
        $cacheKey = 'register_pending_' . md5($email);
        $pendingData = Cache::get($cacheKey);

        if (!$pendingData) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi telah kedaluwarsa atau tidak ditemukan. Silakan isi form pendaftaran kembali.',
            ], 422);
        }

        // Cek kembali apakah email tiba-tiba sudah terdaftar
        if (User::where('email', $email)->exists()) {
            Cache::forget($cacheKey);
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah terdaftar. Silakan masuk ke akun Anda.',
            ], 422);
        }

        // Verifikasi kecocokan kode OTP
        if (trim((string) $pendingData['otp']) !== trim((string) $validated['code'])) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi salah. Silakan periksa kembali kode di email Anda.',
            ], 422);
        }

        // Kode tervalidasi dengan benar! Daftarkan akun baru ke database
        $isAdmin = ($email === 'hlccntayuni@gmail.com');

        $user = User::create([
            'name' => $pendingData['name'],
            'email' => $email,
            'password' => $pendingData['password'],
            'role' => $isAdmin ? 'admin' : 'user',
            'email_verified_at' => now(),
        ]);

        // Bersihkan cache pendaftaran
        Cache::forget($cacheKey);

        // Buat sesi login
        Auth::login($user);
        if ($request->hasSession()) {
            $request->session()->regenerate();
            $request->session()->put('role', $user->role);
        }

        $redirectUrl = $user->isAdmin() ? route('admin.dashboard') : route('home');

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi email berhasil! Selamat datang di Maybeads.',
            'role' => $user->role,
            'redirect' => $redirectUrl,
        ]);
    }

    /**
     * Resend verification code to the unverified email.
     */
    public function resendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));
        $cacheKey = 'register_pending_' . md5($email);
        $pendingData = Cache::get($cacheKey);

        if (!$pendingData) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftaran tidak ditemukan atau sudah kedaluwarsa. Silakan lakukan pendaftaran ulang.',
            ], 422);
        }

        // Cooldown 60 detik sebelum kirim ulang
        if (isset($pendingData['sent_at']) && (now()->timestamp - $pendingData['sent_at'] < 60)) {
            $remaining = 60 - (now()->timestamp - $pendingData['sent_at']);
            return response()->json([
                'success' => false,
                'message' => "Mohon tunggu {$remaining} detik sebelum meminta kode baru.",
            ], 429);
        }

        // Buat kode OTP baru
        $otp = (string) random_int(100000, 999999);
        $pendingData['otp'] = $otp;
        $pendingData['sent_at'] = now()->timestamp;
        Cache::put($cacheKey, $pendingData, now()->addMinutes(15));

        try {
            Mail::to($email)->send(new VerificationCodeMail($otp, $pendingData['name']));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang email verifikasi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang email: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi baru berhasil dikirimkan ke email Anda.',
        ]);
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
