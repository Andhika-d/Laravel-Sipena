<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'jurusan_prodi',
        'mengajar',
        'kelas_id',
    ];
    public function user()
    {
    return $this->hasOne(User::class, 'guru_id');
    }

    public function nilaiHarian()
    {
        return $this->hasMany(NilaiHarian::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }


}
