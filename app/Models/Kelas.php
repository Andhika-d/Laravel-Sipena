<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $nama
 */
class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
    
    public function guru()
    {
        return $this->hasMany(Guru::class);
    }

}

