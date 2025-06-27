<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = ['nama', 'nis', 'kelas_id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilaiHarian()
    {
        return $this->hasMany(NilaiHarian::class);
    }
}

