<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AbsensiGuru;
use Carbon\Carbon;

class TandaiAlfaGuru extends Command
{
    protected $signature = 'tandai:alfa';
    protected $description = 'Menandai guru yang tidak absen masuk sampai jam 15.00 sebagai alfa';

    public function handle()
    {
        $today = Carbon::today();
        $batasWaktu = Carbon::createFromTime(15, 0);

        if (Carbon::now()->lt($batasWaktu)) {
            $this->info('Masih sebelum jam 15.00, task dibatalkan.');
            return;
        }

        // Ambil ID guru yang sudah absen hari ini
        $guruIdsYangSudahAbsen = AbsensiGuru::whereDate('tanggal', $today)->pluck('user_id')->toArray();

        // Ambil data guru yang belum absen
        $guruBelumAbsen = User::where('role', 'guru')
        ->whereHas('guru')
        ->whereNotIn('id', $guruIdsYangSudahAbsen)
        ->get();

        $jumlahDitandai = 0;

        foreach ($guruBelumAbsen as $guru) {
            AbsensiGuru::updateOrCreate(
                [
                    'user_id' => $guru->id,
                    'tanggal' => $today,
                ],
                [
                    'status_kehadiran' => 'alfa',
                    'is_telat' => false,
                    'status_verifikasi' => false,
                ]
            );

            $jumlahDitandai++;
        }

        $this->info("Total guru ditandai alfa: $jumlahDitandai");
    }
}