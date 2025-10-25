<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    protected $fillable = [
        'keterangan',
        'gambar',
        'siswa_id',
        'created_by',
    ];


    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
