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
                'nama' => 'ADMIN SMKN 1 GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'adminsmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'PIKET SMKN 1 GORONGTALO',
                'role' => User::ROLE_GURU_PIKET,
                'username' => 'piketsmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'KESISWAAN SMKN 1 GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaansmea25',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Rizal Pakaya',
                'role' => User::ROLE_GURU,
                'username' => 'rizalpakaya',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Siti Aminah',
                'role' => User::ROLE_GURU,
                'username' => 'sitiaminah',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Budi Santoso',
                'role' => User::ROLE_GURU,
                'username' => 'budisantoso',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Dewi Kartika',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'dewikartika',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Agus Salim',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'agussalim',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Lina Marlina',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'linamarlina',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Hendra Wijaya',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'hendrawijaya',
                'password' => bcrypt('password123'),
            ],
            [
                'nama' => 'Slamet Riyadi',
                'role' => User::ROLE_KEPSEK,
                'username' => 'slametriyadi',
                'password' => bcrypt('password123'),
            ],
        ]);
    }
}
