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
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'adminsmea25',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'PIKET SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_GURU_PIKET,
                'username' => 'piketsmea25',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaansmea25',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Rizal Pakaya',
                'role' => User::ROLE_GURU,
                'username' => 'rizalpakaya',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'role' => User::ROLE_GURU,
                'username' => 'sitiaminah',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'role' => User::ROLE_GURU,
                'username' => 'budisantoso',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Dewi Kartika',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'dewikartika',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Agus Salim',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'agussalim',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Lina Marlina',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'linamarlina',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Hendra Wijaya',
                'role' => User::ROLE_ORANGTUA,
                'username' => 'hendrawijaya',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'Slamet Riyadi',
                'role' => User::ROLE_KEPSEK,
                'username' => 'slametriyadi',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'SATPAM SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SATPAM,
                'username' => 'satpamsmea25',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea25',
                'password' => bcrypt('12345678'),
                'created_at' => now(),




                // SUPERADMIN
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin1',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin2',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin3',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin4',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin5',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin6',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin7',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin8',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin9',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin10',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin11',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin12',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin13',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin14',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin15',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
            [
                'nama' => 'ADMIN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_SUPERADMIN,
                'username' => 'admin16',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
             [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea1',
                'password' => bcrypt('12345678'),
                'created_at' => now(),





                // BIMBINGAN KONSELING
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea3',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea4',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea5',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea6',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea7',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea8',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea9',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea10',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea11',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea12',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea13',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea14',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea15',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'BK SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_BK,
                'username' => 'bksmea16',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],




            // KESISWAAN

             [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan1',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan2',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan3',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan4',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan5',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan6',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan7',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan8',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan9',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan10',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan11',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan12',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan13',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan14',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan15',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ], [
                'nama' => 'KESISWAAN SMKN 1 KOTA GORONGTALO',
                'role' => User::ROLE_KESISWAAN,
                'username' => 'kesiswaan16',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
            ],
        ]);
    }
}
