<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeterlambatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('keterlambatans')->insert([
            [
                'keterangan' => 'Terlambat karena hujan deras di pagi hari.',
                'gambar' => null,
                'siswa_id' => 1,
                'created_by' => 2,
                'date' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'keterangan' => 'Rantai sepeda terlepas di perjalanan.',
                'gambar' => null,
                'siswa_id' => 2,
                'created_by' => 2,
                'date' => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'keterangan' => 'Terlambat karena membantu orang tua di rumah.',
                'gambar' => null,
                'siswa_id' => 3,
                'created_by' => 3,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Bangun kesiangan karena alarm tidak berbunyi.',
                'gambar' => null,
                'siswa_id' => 4,
                'created_by' => 3,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Transportasi umum mengalami mogok di tengah jalan.',
                'gambar' => null,
                'siswa_id' => 5,
                'created_by' => 1,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
