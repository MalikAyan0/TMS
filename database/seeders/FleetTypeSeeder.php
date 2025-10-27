<?php

namespace Database\Seeders;

use App\Models\FleetType;
use App\Models\User;
use Illuminate\Database\Seeder;

class FleetTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get admin user ID for created_by field
    $adminUser = User::where('email', 'admin@example.com')->first()
      ?? User::orderBy('id')->first();

    if (!$adminUser) {
      throw new \Exception('No user found to set as creator for fleet types');
    }

    $fleetTypes = [
      [
        'title' => 'Trailer',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Container Carrier',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Truck',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Van',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Car',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Pickup',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Semi-Trailer',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Flatbed',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Tanker',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'title' => 'Refrigerated Truck',
        'image' => null,
        'created_by' => $adminUser->id
      ],
    ];

    foreach ($fleetTypes as $type) {
      FleetType::create($type);
    }
  }
}
