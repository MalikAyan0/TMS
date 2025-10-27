<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NatureOfAccount;

class NatureOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'title' => 'Transportation Revenue',
                'code' => 1001,
                'type' => 'revenue',
                'description' => 'Income from transportation services',
                'status' => 'active',
            ],
            [
                'title' => 'Freight Income',
                'code' => 1002,
                'type' => 'revenue',
                'description' => 'Revenue from freight operations',
                'status' => 'active',
            ],
            [
                'title' => 'Fleet Assets',
                'code' => 2001,
                'type' => 'asset',
                'description' => 'Transportation vehicles and equipment',
                'status' => 'active',
            ],
            [
                'title' => 'Cash and Bank',
                'code' => 2002,
                'type' => 'asset',
                'description' => 'Cash in hand and bank accounts',
                'status' => 'active',
            ],
            [
                'title' => 'Fuel Expenses',
                'code' => 3001,
                'type' => 'expense',
                'description' => 'Vehicle fuel and energy costs',
                'status' => 'active',
            ],
            [
                'title' => 'Maintenance Costs',
                'code' => 3002,
                'type' => 'expense',
                'description' => 'Vehicle maintenance and repairs',
                'status' => 'active',
            ],
            [
                'title' => 'Driver Salaries',
                'code' => 3003,
                'type' => 'expense',
                'description' => 'Payments to drivers and operators',
                'status' => 'active',
            ],
            [
                'title' => 'Accounts Payable',
                'code' => 4001,
                'type' => 'liability',
                'description' => 'Outstanding supplier payments',
                'status' => 'active',
            ],
            [
                'title' => 'Loan Payable',
                'code' => 4002,
                'type' => 'liability',
                'description' => 'Outstanding loans and financing',
                'status' => 'active',
            ],
            [
                'title' => 'Owner Equity',
                'code' => 5001,
                'type' => 'equity',
                'description' => 'Owner capital and retained earnings',
                'status' => 'active',
            ],
        ];

        foreach ($accounts as $account) {
            NatureOfAccount::create($account);
        }
    }
}
