<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\PengaturanJam;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // 🔑 Ambil dari tabel pengaturan_jam
        $jamPengaturan = PengaturanJam::first();
        $jamMasukMulai = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_masuk_mulai) : Carbon::createFromTime(6,0);
        $jamMasukSelesai = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_masuk_selesai) : Carbon::createFromTime(7,30);
        $jamTelat       = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_telat) : Carbon::createFromTime(8,0);
        $jamPulang      = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_pulang) : Carbon::createFromTime(14,0);

        $absenHariIni = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();
        
        $lokasiKantor = [
            'latitude' => -6.075751,
            'longitude' => 106.093429
        ];

        return view('guru.absensi.index', compact(
            'guru',
            'absenHariIni',
            'now',
            'jamMasukMulai',
            'jamMasukSelesai',
            'jamTelat',
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

        $jamPengaturan = PengaturanJam::first();
        $jamTelat = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_telat) : Carbon::createFromTime(8,0);

        $sudahAbsen = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Anda sudah absen hari ini.');
        }

        $isTelat = $now->gt($jamTelat);

        AbsensiGuru::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $now,
            'is_telat' => $isTelat,
            'status_kehadiran' => 'hadir',
        ]);

        return back()->with('success', $isTelat ? 'Anda absen telat.' : 'Absen masuk berhasil.');
    }

    // Absen pulang
    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        $jamPengaturan = PengaturanJam::first();
        $jamPulang = $jamPengaturan ? Carbon::createFromFormat('H:i', $jamPengaturan->jam_pulang) : Carbon::createFromTime(14,0);

        $absen = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absen || $absen->jam_pulang) {
            return back()->with('error', 'Data absen tidak ditemukan atau Anda sudah absen pulang.');
        }

        if ($now->lt($jamPulang)) {
            return back()->with('error', 'Absen pulang hanya bisa dilakukan setelah jam '.$jamPulang->format('H:i').'.');
        }

        $absen->update([
            'jam_pulang' => $now,
            'status_verifikasi' => true,
        ]);

        return back()->with('success', 'Absen pulang berhasil.');
    }

    // Ajukan izin
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