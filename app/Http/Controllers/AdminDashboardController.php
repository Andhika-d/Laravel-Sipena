<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();

        return view('admin.dashboard', compact('totalSiswa', 'totalGuru', 'totalKelas', 'totalMapel'));
    }
}