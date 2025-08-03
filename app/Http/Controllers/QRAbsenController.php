<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AbsensiGuru;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrAbsenController extends Controller
{
    public function generateImage()
    {
        $scanUrl = route('qr.scan'); // URL tujuan saat QR discan
        $qr = QrCode::format('svg')->size(300)->generate($scanUrl);
        return response($qr)->header('Content-Type', 'image/svg+xml');
    }
     public function showStaticQR()
    {
        // URL tujuan QR (misal: /scan-qr)
        $scanUrl = URL::route('qr.scan');

        return view('admin.qr-absen', compact('scanUrl'));
    }
    public function handle(Request $request)
{
    if (!Auth::check()) {
        session(['qr_absen_redirect' => route('qr.absen.redirect')]);
        return redirect()->route('login');
    }

    $user = Auth::user();
    $today = Carbon::today();

    // ⛔ Cek dulu: kalau udah absen masuk & pulang, langsung kasih info
    $absensi = AbsensiGuru::where('user_id', $user->id)
        ->whereDate('tanggal', $today)
        ->first();

    if ($absensi && $absensi->jam_pulang) {
        return redirect()->route('guru.absensi')->with([
            'modal_success' => true,
            'success_message' => 'Anda sudah menyelesaikan absen hari ini.',
            'jarak' => null,
        ]);
    }

    // ✅ Kalau udah absen masuk aja
    if ($absensi && !$absensi->jam_pulang) {
        $now = Carbon::now();
        $minimalJamPulang = Carbon::createFromTime(14, 0);

        if ($now->lt($minimalJamPulang)) {
            return redirect()->route('guru.absensi')->with([
                'modal_info' => true,
                'info_message' => 'Anda sudah melakukan absen masuk hari ini.
                                   Jika ingin melakukan absen pulang menggunakan QR, silakan logout terlebih dahulu, lalu scan ulang QR code.',
                'jarak' => null,
            ]);
        }

        // Kalau udah jam 14.00, baru cek lokasi
        $lokasiUser = [
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ];

        $lokasiKantor = [
            'latitude' => -6.075743,
            'longitude' => 106.093441,
        ];

        $jarak = $this->hitungJarak($lokasiUser, $lokasiKantor);

        if ($jarak > 0.1) {
            return redirect()->route('guru.absensi')->with([
                'modal_error' => true,
                'error_message' => 'Lokasi anda terlalu jauh dari kantor untuk absen pulang.',
                'jarak' => $jarak,
            ]);
        }

        // Simpan jam pulang
        $absensi->update(['jam_pulang' => $now]);

        return redirect()->route('guru.absensi')->with([
            'modal_success' => true,
            'success_message' => 'Absen pulang berhasil.',
            'jarak' => $jarak,
        ]);
    }

    // 📌 Kalau belum absen sama sekali
    $lokasiUser = [
        'latitude' => $request->input('latitude'),
        'longitude' => $request->input('longitude'),
    ];

    $lokasiKantor = [
        'latitude' => -6.075743,
        'longitude' => 106.093441,
    ];

    $jarak = $this->hitungJarak($lokasiUser, $lokasiKantor);

    if ($jarak > 0.1) {
        return redirect()->route('guru.absensi')->with([
            'modal_error' => true,
            'error_message' => 'Lokasi anda terlalu jauh dari kantor.',
            'jarak' => $jarak,
        ]);
    }

    $now = Carbon::now();
    $isTelat = $now->gt(Carbon::createFromTime(8, 0));

    AbsensiGuru::create([
        'user_id' => $user->id,
        'tanggal' => $today,
        'jam_masuk' => $now,
        'is_telat' => $isTelat,
        'status_kehadiran' => 'hadir',
    ]);

    return redirect()->route('guru.absensi')->with([
        'modal_success' => true,
        'success_message' => $isTelat
            ? 'Anda berhasil absen, namun terlambat.'
            : 'Absen masuk berhasil.',
        'jarak' => $jarak,
    ]);
}


    // Tambahkan fungsi bantu untuk hitung jarak
    private function hitungJarak($lokasi1, $lokasi2)
    {
        $earthRadius = 6371; // km
        $latFrom = deg2rad($lokasi1['latitude']);
        $lonFrom = deg2rad($lokasi1['longitude']);
        $latTo = deg2rad($lokasi2['latitude']);
        $lonTo = deg2rad($lokasi2['longitude']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    public function redirect()
    {
        return view('guru.qr.absen-redirect');
    }

}
