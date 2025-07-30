<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $nama_mapel
 */

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $fillable = ['nama_mapel'];

    public function nilaiHarian()
    {
        return $this->hasMany(NilaiHarian::class);
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel');
    }
    

}

