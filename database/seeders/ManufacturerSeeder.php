<?php

namespace Database\Seeders;

use App\Models\FleetManufacturer;
use App\Models\User;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
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
      throw new \Exception('No user found to set as creator for manufacturers');
    }

    $manufacturers = [
      [
        'name' => 'Toyota',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Honda',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Suzuki',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Nissan',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Hino',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Isuzu',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Mercedes-Benz',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'Volvo',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'MAN',
        'image' => null,
        'created_by' => $adminUser->id
      ],
      [
        'name' => 'DAF',
        'image' => null,
        'created_by' => $adminUser->id
      ],
    ];

    foreach ($manufacturers as $manufacturer) {
      FleetManufacturer::create($manufacturer);
    }
  }
}
