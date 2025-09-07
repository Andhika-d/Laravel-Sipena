<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // skip baris pertama (header)
        $rows->skip(1)->each(function($row) {

            $nama         = trim($row[0]);
            $jenisKelamin = strtoupper(trim($row[1]));
            $tipeGuru     = strtolower(trim($row[2]));
            $kelasNama    = trim($row[3]);
            $mapelNama    = trim($row[4]);

            // cari kelas tanpa case sensitive
            $kelas = Kelas::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower($kelasNama)])->first();

            // cari mapel tanpa case sensitive
            $mapel = Mapel::whereRaw('LOWER(TRIM(nama_mapel)) = ?', [strtolower($mapelNama)])->first();

            if ($tipeGuru === 'kelas') {
            if (!$kelas) {
                Log::warning("Kelas {$kelasNama} tidak ditemukan untuk guru {$nama}");
                return;
            }

            $guru = Guru::create([
                'nama' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'tipe_guru' => 'kelas',
                'kelas_id' => $kelas->id,
            ]);

            // auto assign semua mapel kecuali PJOK & PAI
            $mapels = Mapel::whereNotIn('nama_mapel', ['PJOK', 'PAI'])->pluck('id');
            $guru->mapel()->attach($mapels);

        } elseif ($tipeGuru === 'mapel') {
            $guru = Guru::create([
                'nama' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'tipe_guru' => 'mapel',
                'kelas_id' => null,
            ]);

            if ($mapelNama) {
                $mapelList = array_map('trim', explode(',', $mapelNama));
                $mapels = Mapel::whereIn('nama_mapel', $mapelList)->pluck('id');
                $guru->mapel()->attach($mapels);
            }
        }
        });
    }
}
