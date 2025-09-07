<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanJam extends Model
{
    protected $table = 'pengaturan_jam';
    protected $fillable = [
        'jam_masuk_mulai',
        'jam_masuk_selesai',
        'jam_telat',
        'jam_pulang',
    ];
}
