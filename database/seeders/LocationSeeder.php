<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\User;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user as creator
        $user = User::first();
        $userId = $user ? $user->id : null;

        $locations = [
            [
                'title' => 'New York Port Terminal',
                'short_name' => 'NYC-PT',
                'description' => 'Major container terminal located in New York Harbor, handling international shipping containers and cargo operations.',
                'type' => 'port',
                'status' => 'active',
                'country' => 'United States',
                'city' => 'New York',
                'address' => '123 Harbor Drive, Brooklyn, NY',
                'postal_code' => '11232',
                'latitude' => 40.6782,
                'longitude' => -74.0442,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Los Angeles Warehouse Hub',
                'short_name' => 'LA-WH',
                'description' => 'Large-scale warehouse facility for storage and distribution of goods in the Los Angeles metropolitan area.',
                'type' => 'warehouse',
                'status' => 'active',
                'country' => 'United States',
                'city' => 'Los Angeles',
                'address' => '456 Industrial Blvd, Los Angeles, CA',
                'postal_code' => '90021',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Miami International Depot',
                'short_name' => 'MIA-DEP',
                'description' => 'Strategic depot location near Miami International Airport for air cargo and ground transportation coordination.',
                'type' => 'depot',
                'status' => 'maintenance',
                'country' => 'United States',
                'city' => 'Miami',
                'address' => '789 Airport Rd, Miami, FL',
                'postal_code' => '33126',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Chicago Central Office',
                'short_name' => 'CHI-OFF',
                'description' => 'Regional headquarters and administrative office for Midwest operations and customer service.',
                'type' => 'office',
                'status' => 'active',
                'country' => 'United States',
                'city' => 'Chicago',
                'address' => '321 Business Center Dr, Chicago, IL',
                'postal_code' => '60607',
                'latitude' => 41.8781,
                'longitude' => -87.6298,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Houston Oil Terminal',
                'short_name' => 'HOU-OIL',
                'description' => 'Specialized terminal for petroleum products and chemical cargo handling with advanced safety systems.',
                'type' => 'terminal',
                'status' => 'inactive',
                'country' => 'United States',
                'city' => 'Houston',
                'address' => '987 Refinery Rd, Houston, TX',
                'postal_code' => '77029',
                'latitude' => 29.7604,
                'longitude' => -95.3698,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
