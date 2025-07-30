<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailNilaiSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Siswa' => $item->siswa->nama,
                'Kelas' => $item->siswa->kelas->nama,
                'Mapel' => $item->mapel->nama_mapel,
                'Tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'),
                'Deskripsi' => $item->deskripsi ?? '-',
                'Nilai' => $item->nilai,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Siswa', 'Kelas', 'Mapel', 'Tanggal', 'Deskripsi', 'Nilai'];
    }

    public function title(): string
    {
        return 'Detail Nilai';
    }
}
