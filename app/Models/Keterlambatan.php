<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    protected $fillable = [
        'nis',
        'nomor_hp',
        'nama',
        'keterangan',
        'gambar',
        'created_by',
    ];


    function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
