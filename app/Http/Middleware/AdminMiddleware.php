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
        // if not login redirect login page
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman admin.');
        }

        $user = Auth::user();

        // if not admin redirect
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh akun dengan role Administrator.');
        }

        // set session role
        // if session request; get role as admin
        if ($request->session()->get('role') !== 'admin') {
            // put session login as admin (sync login)
            $request->session()->put('role', 'admin');
        }

        // return access to pages
        return $next($request);
    }
}
