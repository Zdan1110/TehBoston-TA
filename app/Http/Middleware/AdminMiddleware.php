<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        // Izinkan hanya jika type_akun adalah kasir atau user
        if (!$user || !in_array(strtolower($user['type_akun']), ['admin', 'auditor'])) {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
