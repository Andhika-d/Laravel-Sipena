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

        $guruIdsYangSudahAbsen = AbsensiGuru::whereDate('tanggal', $today)->pluck('user_id')->toArray();

        $guruBelumAbsen = User::whereHas('guru')
            ->whereNotIn('id', $guruIdsYangSudahAbsen)
            ->get();

        foreach ($guruBelumAbsen as $guru) {
            AbsensiGuru::create([
                'user_id' => $guru->id,
                'tanggal' => $today,
                'status_kehadiran' => 'alfa',
                'is_telat' => false,
                'status_verifikasi' => false,
            ]);
        }

        $this->info("Total guru ditandai alfa: " . count($guruBelumAbsen));
    }
}
