<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
/**
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 *
 * @property string $status_kehadiran
 * @property string|null $deskripsi
 * @property \Illuminate\Http\UploadedFile|null $file_pendukung
 */

class AbsensiGuruController extends Controller
{
    // Halaman form absensi
    public function index()
{
    $user = Auth::user();
    $guru = $user->guru;
    if ($guru) {
        $guru->load('mapel');
    }
    $today = Carbon::today();
    $now = Carbon::now();
    $jamMasukMulai = Carbon::createFromTime(6, 0);
    $jamMasukSelesai = Carbon::createFromTime(7, 30);
    $jamPulang = Carbon::createFromTime(14, 0);

    $absenHariIni = AbsensiGuru::where('user_id', $user->id)
        ->whereDate('tanggal', $today)
        ->first();
    
    $lokasiKantor = [
        'latitude' => -6.075743,
        'longitude' => 106.093441 
    ];

    return view('guru.absensi.index', compact(
        'guru',
        'absenHariIni',
        'now',
        'jamMasukMulai',
        'jamMasukSelesai',
        'jamPulang',
        'lokasiKantor'
    ));
}

    // Absen masuk (hadir atau telat)
    public function absenMasuk(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        $sudahAbsen = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah absen hari ini.');
        }

        $isTelat = $now->gt(Carbon::createFromTime(8, 0, 0)); // telat jika lewat jam 8

        AbsensiGuru::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $now,
            'is_telat' => $isTelat,
            'status_kehadiran' => 'hadir', // <<< tambahin ini bro
        ]);


        return back()->with('success', $isTelat ? 'Anda absen telat.' : 'Absen masuk berhasil.');
    }

    // Absen pulang (hanya bisa setelah jam 14.00)
    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        $absen = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absen || $absen->jam_pulang) {
            return back()->with('error', 'Data absen tidak ditemukan atau Anda sudah absen pulang.');
        }

        if ($now->lt(Carbon::createFromTime(14, 0, 0))) {
            return back()->with('error', 'Absen pulang hanya bisa dilakukan setelah jam 14.00.');
        }

        $absen->update([
            'jam_pulang' => $now,
            'status_verifikasi' => true,
        ]);

        return back()->with('success', 'Absen pulang berhasil.');
    }

    public function ajukanIzin(Request $request)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:izin,sakit',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        $sudahAbsen = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah mengisi absensi hari ini.');
        }

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('izin_sakit', 'public');
        }

        AbsensiGuru::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'status_kehadiran' => $request->status_kehadiran,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $filePath,
            'is_telat' => false,
            'status_verifikasi' => false,
        ]);

        return back()->with('success', 'Pengajuan izin/sakit berhasil dikirim.');
    }
}
