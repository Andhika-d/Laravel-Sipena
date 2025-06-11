<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Ambil prefix route untuk cek role
        $prefix = $request->route()->getPrefix();

        // Contoh prefix: /admin, /guru, /kepsek
        switch ($user->role) {
            case 'admin':
                if ($prefix === '/admin') {
                    return $next($request);
                }
                return redirect('/admin');

            case 'guru':
                if ($prefix === '/guru') {
                    return $next($request);
                }
                return redirect('/guru');

            case 'kepsek':
                if ($prefix === '/kepsek') {
                    return $next($request);
                }
                return redirect('/kepsek');

            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Role tidak valid']);
        }
    }
}