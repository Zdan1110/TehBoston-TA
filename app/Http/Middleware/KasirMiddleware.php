<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KasirMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        // Izinkan hanya jika type_akun adalah kasir atau user
        if (!$user || !in_array(strtolower($user['type_akun']), ['kasir', 'user'])) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
