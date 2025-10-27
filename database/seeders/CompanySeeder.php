<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'title' => 'ABC Transportation Ltd.',
                'short_name' => 'ABC',
                'status' => 'active',
            ],
            [
                'title' => 'Swift Logistics Corporation',
                'short_name' => 'SWIFT',
                'status' => 'active',
            ],
            [
                'title' => 'Express Freight Services',
                'short_name' => 'EFS',
                'status' => 'active',
            ],
            [
                'title' => 'Global Shipping Solutions',
                'short_name' => 'GSS',
                'status' => 'active',
            ],
            [
                'title' => 'Metro Transport Company',
                'short_name' => 'MTC',
                'status' => 'inactive',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}