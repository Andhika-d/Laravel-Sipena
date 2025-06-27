<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        if (Auth::user()->role !== $role) {
            return redirect()->route('login')->with('error', "Anda harus login sebagai {$role} untuk mengakses halaman ini.");
        }

        return $next($request);
    }
}