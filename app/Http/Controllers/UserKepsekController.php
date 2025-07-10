<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use Carbon\Carbon;

class UserKepsekController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $namakepsek = $user->name ?? 'Kepsek Tanpa Nama';

        $tanggalHariIni = Carbon::today();

        // Semua guru aktif
        $semuaGuru = Guru::pluck('id')->toArray();
        $jumlahGuru = count($semuaGuru);

        // Absensi hari ini
        $absensiHariIni = AbsensiGuru::where('tanggal', $tanggalHariIni)->get();

        $jumlahHadir = $absensiHariIni->where('status_kehadiran', 'hadir')->count();
        $jumlahIzin  = $absensiHariIni->where('status_kehadiran', 'izin')->count();
        $jumlahSakit = $absensiHariIni->where('status_kehadiran', 'sakit')->count();
        $jumlahAlfa  = $absensiHariIni->where('status_kehadiran', 'alfa')->count();

        // Guru yang belum absen sama sekali hari ini
        $guruYangSudahAbsen = $absensiHariIni->pluck('user_id')->unique()->toArray();
        $guruBelumAbsen = array_diff($semuaGuru, $guruYangSudahAbsen);
        $jumlahBelumAbsen = count($guruBelumAbsen);

        return view('kepsek.dashboard', compact(
            'namakepsek',
            'jumlahGuru',
            'jumlahHadir',
            'jumlahIzin',
            'jumlahSakit',
            'jumlahAlfa',
            'jumlahBelumAbsen'
        ));
    }
}
