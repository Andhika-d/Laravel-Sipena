<?php

namespace App\Exports;

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapNilaiExport implements WithMultipleSheets
{
    protected $data;
    protected $rataRataPerSiswa;

    public function __construct($data, $rataRataPerSiswa)
    {
        $this->data = $data;
        $this->rataRataPerSiswa = $rataRataPerSiswa;
    }

    public function sheets(): array
    {
        return [
            new \App\Exports\DetailNilaiSheet($this->data),
            new \App\Exports\RataRataSheet($this->rataRataPerSiswa),
        ];
    }
}
