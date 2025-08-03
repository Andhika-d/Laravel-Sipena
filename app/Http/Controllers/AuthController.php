<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
{
    return view('auth.login');
}

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if (session()->has('qr_absen_redirect')) {
            $url = session()->pull('qr_absen_redirect');

            // Cegah langsung nyasar ke /handle
            if (str_contains($url, '/handle')) {
                $url = str_replace('/handle', '/redirect', $url);
            }

            return redirect()->to($url);
        }

        // Redirect by role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('kepsek.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah',
    ]);
}


public function logout()
{
    Auth::logout();
    return redirect('/login');
}

}


