<?php

namespace App\Imports;

use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        if (!isset($rows[0]['nama_kelas'])) {
            $this->errors[] = 'Format kolom tidak sesuai. Pastikan header kolom adalah: nama_kelas';
            return;
        }

        foreach ($rows as $index => $row) {
            $data = [
                'nama' => $row['nama_kelas'] ?? null,
            ];

            $validator = Validator::make($data, [
                'nama' => 'required|string|max:255',
            ], [
                'nama.required' => 'Nama kelas wajib diisi.',
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Baris ke-" . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            Kelas::create($data);
        }
    }
}

