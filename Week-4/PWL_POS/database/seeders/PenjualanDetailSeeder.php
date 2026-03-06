<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($penjualan = 1; $penjualan <= 10; $penjualan++) {
            for ($i = 1; $i <= 3; $i++) {
                $data[] = [
                    'penjualan_id' => $penjualan,
                    'barang_id' => rand(1, 15),
                    'harga' => rand(25000, 50000),
                    'jumlah' => rand(1, 5),
                ];
            }
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}