<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Perizinan;

class PerizinanSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        DB::table('perizinans')->insert([
            [
                'keterangan' => 'Izin keluar sekolah karena harus kontrol ke rumah sakit.',
                'gambar' => 'uploads/perizinan/keluar_rs.jpg',
                'status' => Perizinan::STATUS_APPROVED,
                'siswa_id' => 1,
                'created_by' => 2,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'keterangan' => 'Izin keluar sekolah karena ada urusan keluarga mendesak.',
                'gambar' => 'uploads/perizinan/keluar_keluarga.jpg',
                'status' => Perizinan::STATUS_PENDING,
                'siswa_id' => 2,
                'created_by' => 3,
                'created_at' => now()->subDay(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Izin keluar sekolah untuk mengikuti lomba antar sekolah di luar kota.',
                'gambar' => 'uploads/perizinan/keluar_lomba.jpg',
                'status' => Perizinan::STATUS_APPROVED,
                'siswa_id' => 3,
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keterangan' => 'Izin keluar sekolah karena dipanggil orang tua mendadak.',
                'gambar' => 'uploads/perizinan/keluar_orangtua.jpg',
                'status' => Perizinan::STATUS_REJECTED,
                'siswa_id' => 4,
                'created_by' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDay(),
            ],
            [
                'keterangan' => 'Izin keluar sekolah untuk membeli obat di apotek terdekat.',
                'gambar' => 'uploads/perizinan/keluar_apotek.jpg',
                'status' => Perizinan::STATUS_PENDING,
                'siswa_id' => 5,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
