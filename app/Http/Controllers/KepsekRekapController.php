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

        $hadir = $absensi->where('status_kehadiran', 'hadir')->count();
        $izin  = $absensi->where('status_kehadiran', 'izin')->count();
        $sakit = $absensi->where('status_kehadiran', 'sakit')->count();
        $alfa  = $absensi->where('status_kehadiran', 'alfa')->count();

        $totalHariKerja = $hadir + $izin + $sakit + $alfa;
        $totalPoin = $hadir + ($izin * 0.5) + ($sakit * 0.5);
        $persentase = $totalHariKerja > 0 ? round(($totalPoin / $totalHariKerja) * 100, 2) : 0;

        $rekap[] = [
            'nama_guru' => $guru->nama,
            'hadir' => $hadir,
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

