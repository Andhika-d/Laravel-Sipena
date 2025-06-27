<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiHarian extends Model
{
    protected $table = 'nilai_harian';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'guru_id',
        'tanggal',
        'deskripsi',
        'nilai',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

