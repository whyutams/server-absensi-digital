<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
   protected $fillable = [
        'nis',
        'nama',
        'nomor_hp',
        'jurusan_id',
        'kelas_id',
        'orangtua_id',
    ];

    public function orangtua()
    {
        return $this->belongsTo(User::class, 'orangtua_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
