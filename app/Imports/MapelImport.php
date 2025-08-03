<?php

namespace App\Imports;

use App\Models\Mapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MapelImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
{
    if (!isset($rows[0]['nama_mapel'])) {
        $this->errors[] = 'Format kolom tidak sesuai. Pastikan header kolom adalah: nama_mapel';
        return;
    }

    foreach ($rows as $index => $row) {
        $rowData = [
            'nama_mapel' => $row['nama_mapel'] ?? null,
        ];

        $validator = Validator::make($rowData, [
            'nama_mapel' => 'required|string|max:255',
        ], [
            'nama_mapel.required' => 'Nama mapel wajib diisi.',
            'nama_mapel.string'   => 'Nama mapel harus berupa teks.',
            'nama_mapel.max'      => 'Nama mapel tidak boleh lebih dari 255 karakter.',
        ]);

        if ($validator->fails()) {
            $this->errors[] = 'Baris ke-' . ($index + 2) . ': ' . implode(', ', $validator->errors()->all());
            continue;
        }

        Mapel::create($rowData);
    }
}
}