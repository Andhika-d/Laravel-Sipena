<?php
namespace App\Exports;

use App\Models\Guru;
use App\Models\AbsensiGuru;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapAbsensiExport implements FromView
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $guruList = Guru::whereHas('user', function ($query) {
            $query->where('role', 'guru');
        })->with('user')->get();
        $rekap = [];

        foreach ($guruList as $guru) {
            $user = $guru->user;
            if (!$user) continue;

            $absensi = AbsensiGuru::where('user_id', $user->id)
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
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

        return view('exports.rekap_absensi', [
            'rekap' => $rekap,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
