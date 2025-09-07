<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengaturanJam;

class AdvanceController extends Controller
{
    public function settingJam()
    {
        $jam = PengaturanJam::first(); // ambil record pertama
        return view('admin.advance.settingjam', compact('jam'));
    }

    public function updateJam(Request $request)
    {
        $request->merge([
            'jam_masuk_mulai' => substr($request->jam_masuk_mulai, 0, 5),
            'jam_masuk_selesai' => substr($request->jam_masuk_selesai, 0, 5),
            'jam_telat' => substr($request->jam_telat, 0, 5),
            'jam_pulang' => substr($request->jam_pulang, 0, 5),
        ]);

        $request->validate([
            'jam_masuk_mulai' => 'required|date_format:H:i',
            'jam_masuk_selesai' => 'required|date_format:H:i',
            'jam_telat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
        ]);

        PengaturanJam::updateOrCreate(
            ['id' => 1], // diasumsikan cuma ada 1 record
            $request->all()
        );

        return redirect()->route('admin.advance.settingjam')
                        ->with('success', 'Pengaturan jam berhasil diperbarui.');
    }
}

