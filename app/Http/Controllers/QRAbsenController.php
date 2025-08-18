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
        
        if (!$request->filled('latitude') || !$request->filled('longitude')) {
        return redirect()->route('qr.absen.redirect');
        }

        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();
        $minimalJamPulang = Carbon::createFromTime(14, 0);

        $absensi = AbsensiGuru::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // ✅ Sudah absen masuk & pulang
        if ($absensi && $absensi->jam_pulang) {
            return redirect()->route('guru.absensi')->with([
                'modal_success' => true,
                'success_message' => 'Absensi hari ini sudah selesai. Tidak perlu scan QR lagi. 👍',
                'jarak' => null,
            ]);
        }

        // ✅ Sudah absen masuk, belum pulang
        if ($absensi && !$absensi->jam_pulang) {
            if ($now->lt($minimalJamPulang)) {
                return redirect()->route('guru.absensi')->with([
                    'modal_info' => true,
                    'info_message' => 'Absen masuk sudah tercatat. Silakan scan ulang QR setelah jam 14.00 untuk absen pulang.',
                    'jarak' => null,
                ]);
            }

            // Cek lokasi
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
                    'error_message' => 'Anda berada di luar area kantor. Absen pulang hanya dapat dilakukan di lokasi yang ditentukan.',
                    'jarak' => $jarak,
                ]);
            }

            // ✅ Catat jam pulang
            $absensi->update([
                'jam_pulang' => $now,
                'status_verifikasi' => true,
            ]);

            return redirect()->route('guru.absensi')->with([
                'modal_success' => true,
                'success_message' => 'Absen pulang berhasil. Terima kasih dan sampai jumpa besok! 🙌',
                'jarak' => $jarak,
            ]);
        }

        // ✅ Belum absen sama sekali → absen masuk
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
                'error_message' => 'Lokasi Anda terlalu jauh dari kantor. Silakan berada di sekitar lokasi kantor untuk absen.',
                'jarak' => $jarak,
            ]);
        }

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
                ? 'Absen masuk berhasil, namun Anda terlambat. Harap lebih tepat waktu ke depannya.'
                : 'Absen masuk berhasil. Selamat bekerja! 💼',
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
