<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalesTaxTerritory;

class SalesTaxTerritorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $territories = [
            [
                'head' => 'Regional Tax Office',
                'title' => 'California State Tax Territory',
                'short_name' => 'CA-TAX',
                'address' => '1234 State St, Sacramento, CA 95814, United States',
                'status' => 'active',
            ],
            [
                'head' => 'District Tax Authority',
                'title' => 'New York City Tax District',
                'short_name' => 'NYC-TAX',
                'address' => '100 Church St, New York, NY 10007, United States',
                'status' => 'active',
            ],
            [
                'head' => 'County Tax Commission',
                'title' => 'Texas State Tax Territory',
                'short_name' => 'TX-TAX',
                'address' => '1011 San Jacinto Blvd, Austin, TX 78701, United States',
                'status' => 'active',
            ],
            [
                'head' => 'Provincial Tax Office',
                'title' => 'Florida State Tax Territory',
                'short_name' => 'FL-TAX',
                'address' => '400 S Monroe St, Tallahassee, FL 32399, United States',
                'status' => 'active',
            ],
            [
                'head' => 'Metropolitan Tax Authority',
                'title' => 'Illinois State Tax Territory',
                'short_name' => 'IL-TAX',
                'address' => '100 W Randolph St, Chicago, IL 60601, United States',
                'status' => 'inactive',
            ],
        ];

        foreach ($territories as $territory) {
            SalesTaxTerritory::create($territory);
        }
    }
}
