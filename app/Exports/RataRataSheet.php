<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RataRataSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->values()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Siswa' => $item['nama'],
                'Kelas' => $item['kelas'],
                'Mapel' => $item['mapel'],
                'Rata-rata' => number_format($item['rata_rata'], 2),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Siswa', 'Kelas', 'Mapel', 'Rata-rata'];
    }

    public function title(): string
    {
        return 'Rata - Rata';
    }
}
