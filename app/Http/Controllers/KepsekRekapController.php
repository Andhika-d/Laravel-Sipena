<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use Carbon\Carbon;
use DB;

class KepsekRekapController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('bulan')) {
            $parsedDate = Carbon::createFromFormat('Y-m', $request->input('bulan'));
            $bulan = $parsedDate->format('m');
            $tahun = $parsedDate->format('Y');
        } else {
            $bulan = date('m');
            $tahun = date('Y');
        }

        // Ambil semua guru
        $guruList = Guru::whereHas('user', function ($query) {
            $query->where('role', 'guru');
        })->with('user')->get();

        $rekap = [];

        foreach ($guruList as $guru) {
        $user = $guru->user;

        if (!$user) continue;

        $absensi = AbsensiGuru::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $hadir_terverifikasi = $absensi
            ->where('status_kehadiran', 'hadir')
            ->where('status_verifikasi', true)
            ->count();

        $hadir_belum_verifikasi = $absensi
            ->where('status_kehadiran', 'hadir')
            ->where('status_verifikasi', false)
            ->count();

        $izin  = $absensi->where('status_kehadiran', 'izin')->count();
        $sakit = $absensi->where('status_kehadiran', 'sakit')->count();
        $alfa  = $absensi->where('status_kehadiran', 'alfa')->count();

        $totalHariAbsensi = $absensi->count(); // Termasuk semua status
        $totalHariKerja = $hadir_terverifikasi + $hadir_belum_verifikasi + $izin + $sakit + $alfa;
        $totalPoin = 
            ($hadir_terverifikasi * 1) +
            ($hadir_belum_verifikasi * 0.5) +
            ($izin * 0.75) +
            ($sakit * 0.75);

        if ($hadir_terverifikasi + $hadir_belum_verifikasi == 0) {
            $persentase = 0;
        } else {
            $persentase = $totalHariKerja > 0 ? round(($totalPoin / $totalHariKerja) * 100, 2) : 0;
        }

        $rekap[] = [
            'nama_guru' => $guru->nama,
            'hadir_lengkap' => $hadir_terverifikasi,
            'hadir_belum_lengkap' => $hadir_belum_verifikasi,
            'izin' => $izin,
            'sakit' => $sakit,
            'alfa' => $alfa,
            'total' => $totalPoin,
            'persentase' => $persentase,
        ];
    }

        return view('kepsek.rekap_absensi', compact('rekap', 'bulan', 'tahun'));
    }
}

