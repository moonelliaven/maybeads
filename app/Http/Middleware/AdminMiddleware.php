<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman admin.');
        }

        $user = Auth::user();

        // 2. Pastikan role user adalah 'admin'
        if (!$user->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh akun dengan role Administrator.');
        }

        // Sinkronisasi session role
        if ($request->session()->get('role') !== 'admin') {
            $request->session()->put('role', 'admin');
        }

        return $next($request);
    }
}
