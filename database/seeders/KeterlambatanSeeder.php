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
                'gambar' => 'uploads/keterlambatan/hujan.jpg',
                'siswa_id' => 1, // pastikan id siswa ini ada di tabel siswas
                'created_by' => 2, // id user (guru piket/kesiswaan)
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'keterangan' => 'Rantai sepeda terlepas di perjalanan.',
                'gambar' => 'uploads/keterlambatan/sepeda.jpg',
                'siswa_id' => 2,
                'created_by' => 2,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'keterangan' => 'Terlambat karena membantu orang tua di rumah.',
                'gambar' => 'uploads/keterlambatan/orangtua.jpg',
                'siswa_id' => 3,
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Bangun kesiangan karena alarm tidak berbunyi.',
                'gambar' => 'uploads/keterlambatan/alarm.jpg',
                'siswa_id' => 4,
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Transportasi umum mengalami mogok di tengah jalan.',
                'gambar' => 'uploads/keterlambatan/mogok.jpg',
                'siswa_id' => 5,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
