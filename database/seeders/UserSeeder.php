<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Admin',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'adminsmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Piket',
                'role' => User::ROLE_GURU_PIKET,
                'username' => 'piketsmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Kesiswaan',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaansmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Rizal Pakaya',
                'role' => User::ROLE_GURU,
                'username' => 'gurusmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Siti Aminah',
                'role' => User::ROLE_GURU,
                'username' => 'gurusmea26',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Budi Santoso',
                'role' => User::ROLE_GURU,
                'username' => 'gurusmea27',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Dewi Kartika',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'ortu1',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Agus Salim',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'ortu2',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Lina Marlina',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'ortu3',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Hendra Wijaya',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'ortu4',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Slamet Riyadi',
                'role' => User::ROLE_KEPSEK,
                'username' => 'kepseksmea25',
                'password' => bcrypt('password123'),
            ],
        ]);
    }
}
