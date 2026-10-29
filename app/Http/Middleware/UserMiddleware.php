<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->role !== 'user') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk pengguna.');
        }

        return $next($request);
    }
}
