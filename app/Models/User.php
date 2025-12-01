<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'nama',
        'nomor_hp',
        'email',
        'alamat',
        'foto_profil',
        'role',
        'jurusan_id',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_GURU_PIKET = 'guru_piket';
    const ROLE_GURU = 'guru';
    const ROLE_KESISWAAN = 'kesiswaan';
    const ROLE_KEPSEK = 'kepsek';
    const ROLE_ORANGTUA = 'orangtua';
    const ROLE_SATPAM = 'satpam';
    const ROLE_BK = 'bimbingan_konseling';
}
