<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            ['supplier_kode' => 'SP01', 'supplier_nama' => 'Supplier A'],
            ['supplier_kode' => 'SP02', 'supplier_nama' => 'Supplier B'],
            ['supplier_kode' => 'SP03', 'supplier_nama' => 'Supplier C'],
        ]);
    }
}