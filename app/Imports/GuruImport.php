<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Guru([
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'], // 'L' atau 'P'
            'kelas_id' => $row['kelas_id'],
        ]);
    }
}
