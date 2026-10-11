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
        'kelas',
        'orangtua_id',
        'created_by',
        'updated_by',
    ];

    public function orangtua()
    {
        return $this->belongsTo(User::class, 'orangtua_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
