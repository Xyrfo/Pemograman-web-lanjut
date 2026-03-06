<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'user_id' => 1,
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'PJ' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'penjualan_tanggal' => now(),
            ];
        }

        DB::table('t_penjualan')->insert($data);
    }
}