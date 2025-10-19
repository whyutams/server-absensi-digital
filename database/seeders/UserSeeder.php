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
                'nama' => 'Admin SMKN ',
                'role' => User::ROLE_ADMIN,
                'username' => 'adminsmea123',
                'password' => bcrypt('password'),
            ],
        ]);

        
    }
}
