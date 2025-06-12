<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGuruController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $namaGuru = $user->guru->nama ?? 'Guru Tanpa Nama';

        return view('guru.dashboard', compact('namaGuru'));
    }

}
