<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siswas')->insert([
            [
                'nis' => '23001',
                'nama' => 'Ahmad Fauzan',
                'nomor_hp' => '081234567890',
                'kelas' => 'X PPLG 1',
                'jurusan_id' => 1, // Pengembangan Perangkat Lunak dan Gim
                'orangtua_id' => 4, // id user orang tua
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '23002',
                'nama' => 'Siti Rahmawati',
                'nomor_hp' => '081345678901',
                'kelas' => 'XI TJKT 1',
                'jurusan_id' => 2, // Teknik Jaringan Komputer dan Telekomunikasi
                'orangtua_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '23003',
                'nama' => 'Dewi Lestari',
                'nomor_hp' => '081356789012',
                'kelas' => 'XII DKV 1',
                'jurusan_id' => 3, // Desain Komunikasi Visual
                'orangtua_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '23004',
                'nama' => 'Rizky Maulana',
                'nomor_hp' => '081367890123',
                'kelas' => 'XI BP 1',
                'jurusan_id' => 4, // Broadcasting dan Perfilman
                'orangtua_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '23005',
                'nama' => 'Nabila Sari',
                'nomor_hp' => '081378901234',
                'kelas' => 'X AKL 1',
                'jurusan_id' => 6, // Akuntansi dan Keuangan Lembaga
                'orangtua_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
