<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'KT01', 'kategori_nama' => 'Makanan'],
            ['kategori_kode' => 'KT02', 'kategori_nama' => 'Minuman'],
            ['kategori_kode' => 'KT03', 'kategori_nama' => 'ATK'],
            ['kategori_kode' => 'KT04', 'kategori_nama' => 'Elektronik'],
            ['kategori_kode' => 'KT05', 'kategori_nama' => 'Kebutuhan Harian'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}