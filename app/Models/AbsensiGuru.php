<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_guru';

    protected $fillable = [
    'user_id',
    'tanggal',
    'jam_masuk',
    'jam_pulang',
    'status_kehadiran',
    'deskripsi',
    'file_pendukung',
    'is_telat',
    'status_verifikasi',
    ];

    protected $casts = [
    'jam_masuk' => 'datetime',
    'jam_pulang' => 'datetime',
];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
