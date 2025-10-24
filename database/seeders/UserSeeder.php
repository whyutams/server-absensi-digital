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
        ]);

        
    }
}
