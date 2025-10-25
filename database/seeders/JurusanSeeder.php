<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jurusans')->insert([
            [
                'nama' => 'Pengembangan Perangkat Lunak dan Gim',
                'alias' => 'PPLG',
                'deskripsi' => 'Jurusan yang fokus pada pengembangan perangkat lunak dan gim.',
            ],
            [
                'nama' => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'alias' => 'TJKT',
                'deskripsi' => 'Jurusan yang fokus pada jaringan komputer dan telekomunikasi.',
            ],
            [
                'nama' => 'Desain Komunikasi Visual',
                'alias' => 'DKV',
                'deskripsi' => 'Jurusan yang fokus pada desain komunikasi visual.',
            ],
            [
                'nama' => 'Broadcasting dan Perfilman',
                'alias' => 'BP',
                'deskripsi' => 'Jurusan yang fokus pada broadcasting dan perfilman.',
            ],
            [
                'nama' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'alias' => 'MPLB',
                'deskripsi' => 'Jurusan yang fokus pada manajemen perkantoran dan layanan bisnis.',
            ],
            [
                'nama' => 'Akuntansi dan Keuangan Lembaga',
                'alias' => 'AKL',
                'deskripsi' => 'Jurusan yang fokus pada akuntansi dan keuangan lembaga.',
            ],
            [
                'nama' => 'Pemasaran',
                'alias' => 'PM',
                'deskripsi' => 'Jurusan yang fokus pada pemasaran.',
            ],
            [
                'nama' => 'Usaha Layanan Pariwisata',
                'alias' => 'ULP',
                'deskripsi' => 'Jurusan yang fokus pada usaha layanan pariwisata.',
            ],
            [
                'nama' => 'Kimia Industri',
                'alias' => 'KI',
                'deskripsi' => 'Jurusan yang fokus pada kimia industri.',
            ]
        ]);
    }
}
