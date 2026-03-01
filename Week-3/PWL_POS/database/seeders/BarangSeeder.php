<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                'kategori_id' => rand(1, 5),
                'barang_kode' => 'BR' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'barang_nama' => 'Barang ' . $i,
                'harga_beli' => rand(5000, 15000),
                'harga_jual' => rand(20000, 40000),
            ];
        }

        DB::table('m_barang')->insert($data);
    }
}