<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->insert([
            [
                'nama' => 'Wahyu Tams Dev Admin',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'whyutams',
                'password' => bcrypt('password!.'),
                'created_at' => now(),
            ],
        ]);
    }
}
