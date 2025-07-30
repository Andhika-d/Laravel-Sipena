<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\AbsensiGuru;
use App\Models\NilaiHarian;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();

        $historiAbsensi = AbsensiGuru::with('guru')->latest('tanggal')->take(2)->get();
        $semuaHistoriAbsensi = AbsensiGuru::latest()->get();
        $historiNilai = NilaiHarian::with('guru')->latest('tanggal')->take(2)->get();
        $semuaHistoriNilai = NilaiHarian::latest()->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalMapel',
            'historiAbsensi', 'historiNilai', 'semuaHistoriAbsensi', 'semuaHistoriNilai'
        ));
    }
}