<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'                => \App\Http\Middleware\AdminMiddleware::class,
            'user'                 => \App\Http\Middleware\UserMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // Cegah back-history cache di semua route web
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // Arahkan guest yang belum login/sudah logout ke halaman login
        $middleware->redirectGuestsTo(function (Request $request) {
            session()->flash('error', 'Silakan masuk (login) terlebih dahulu untuk mengakses dashboard.');
            return route('login');
        });

        // Arahkan user yang sudah login jika mengakses halaman guest (seperti /login)
        $middleware->redirectUsersTo(function () {
            if (Auth::check() && Auth::user()->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('main.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
