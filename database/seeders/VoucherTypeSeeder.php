<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voucherTypes = [
            ['title' => 'Cash Receipt', 'status' => 'active'],
            ['title' => 'Cash Payment', 'status' => 'active'],
            ['title' => 'Bank Receipt', 'status' => 'active'],
            ['title' => 'Bank Payment', 'status' => 'active'],
            ['title' => 'Journal Entry', 'status' => 'active'],
            ['title' => 'Purchase Voucher', 'status' => 'active'],
            ['title' => 'Sales Voucher', 'status' => 'active'],
            ['title' => 'Contra Voucher', 'status' => 'inactive'],
            ['title' => 'Debit Note', 'status' => 'active'],
            ['title' => 'Credit Note', 'status' => 'active'],
        ];

        foreach ($voucherTypes as $voucherType) {
            VoucherType::create($voucherType);
        }
    }
}
