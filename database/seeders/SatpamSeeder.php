<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SatpamSeeder extends Seeder
{
    /**
     * Jalankan database seedernya.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Satpam1',
                'email' => 'satpam1@sekolah.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SATPAM,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Satpam2',
                'email' => 'satpam2@sekolah.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SATPAM,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
