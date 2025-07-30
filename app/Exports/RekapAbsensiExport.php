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

            $hadir_terverifikasi = $absensi
            ->where('status_kehadiran', 'hadir')
            ->where('status_verifikasi', true)
            ->count();
            $hadir_belum_verifikasi = $absensi
            ->where('status_kehadiran', 'hadir')
            ->where('status_verifikasi', false)
            ->count();
            $hadir = $absensi->where('status_kehadiran', 'hadir')->count();
            $izin  = $absensi->where('status_kehadiran', 'izin')->count();
            $sakit = $absensi->where('status_kehadiran', 'sakit')->count();
            $alfa  = $absensi->where('status_kehadiran', 'alfa')->count();
            $totalHariKerja = $hadir_terverifikasi + $hadir_belum_verifikasi + $izin + $sakit + $alfa;
            $totalPoin = 
            ($hadir_terverifikasi * 1) +
            ($hadir_belum_verifikasi * 0.5) +
            ($izin * 0.75) +
            ($sakit * 0.75);
            $persentase = $totalHariKerja > 0 ? round(($totalPoin / $totalHariKerja) * 100, 2) : 0;

            $rekap[] = [
                'nama_guru' => $guru->nama,
                'hadir_lengkap' => $hadir_terverifikasi,
                'hadir_belum_lengkap' => $hadir_belum_verifikasi,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'total' => $totalPoin,
                'persentase' => $totalHariKerja > 0
                                ? round(($totalPoin / $totalHariKerja) * 100, 2)
                                : 0,
            ];
        }

        return view('exports.rekap_absensi', [
            'rekap' => $rekap,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
