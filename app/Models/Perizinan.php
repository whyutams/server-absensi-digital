<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    protected $fillable = [
        'nis',
        'nomor_hp',
        'nama',
        'gambar',
        'status',
        'created_by',
    ];

    function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
}
