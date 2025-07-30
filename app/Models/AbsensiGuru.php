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

    public function guru()
    {
        return $this->hasOneThrough(
            \App\Models\Guru::class,
            \App\Models\User::class,
            'id',        // users.id
            'id',        // gurus.id
            'user_id',   // absensi_guru.user_id
            'guru_id'    // users.guru_id
        );
    }
}
