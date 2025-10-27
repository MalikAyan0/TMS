<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\FleetManufacturer;
use App\Models\FleetType;
use App\Models\User;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get manufacturer and fleet type IDs
    $manufacturerIds = FleetManufacturer::pluck('id')->toArray();
    $fleetTypeIds = FleetType::pluck('id')->toArray();

    // Get admin user ID for created_by field
    $adminUser = User::where('email', 'admin@example.com')->first()
      ?? User::orderBy('id')->first();

    if (!$adminUser || empty($manufacturerIds) || empty($fleetTypeIds)) {
      throw new \Exception('Required data missing for fleet seeder');
    }

    $fleets = [
      [
        'fleet_manufacturer_id' => $manufacturerIds[0], // Toyota
        'fleet_type_id' => $fleetTypeIds[0], // Trailer
        'first_driver' => 'Ahmed Khan',
        'second_driver' => 'Bilal Ahmed',
        'name' => 'Toyota Trailer TK-1234',
        'registration_number' => 'TK-1234',
        'registration_city' => 'Karachi',
        'ownership' => 'Company',
        'diesel_opening_inventory' => 100.00,
        'created_by' => $adminUser->id,
      ],
      [
        'fleet_manufacturer_id' => $manufacturerIds[4], // Hino
        'fleet_type_id' => $fleetTypeIds[1], // Container Carrier
        'first_driver' => 'Zubair Hassan',
        'second_driver' => 'Nadeem Malik',
        'name' => 'Hino Container TK-5678',
        'registration_number' => 'TK-5678',
        'registration_city' => 'Lahore',
        'ownership' => 'Company',
        'diesel_opening_inventory' => 150.00,
        'created_by' => $adminUser->id,
      ],
      [
        'fleet_manufacturer_id' => $manufacturerIds[6], // Mercedes-Benz
        'fleet_type_id' => $fleetTypeIds[2], // Truck
        'first_driver' => 'Waseem Akram',
        'second_driver' => 'Imran Ali',
        'name' => 'Mercedes Truck TK-9012',
        'registration_number' => 'TK-9012',
        'registration_city' => 'Islamabad',
        'ownership' => 'Leased',
        'diesel_opening_inventory' => 200.00,
        'created_by' => $adminUser->id,
      ],
      [
        'fleet_manufacturer_id' => $manufacturerIds[7], // Volvo
        'fleet_type_id' => $fleetTypeIds[6], // Semi-Trailer
        'first_driver' => 'Rizwan Hussain',
        'second_driver' => null,
        'name' => 'Volvo Semi-Trailer TK-3456',
        'registration_number' => 'TK-3456',
        'registration_city' => 'Faisalabad',
        'ownership' => 'Company',
        'diesel_opening_inventory' => 175.00,
        'created_by' => $adminUser->id,
      ],
      [
        'fleet_manufacturer_id' => $manufacturerIds[5], // Isuzu
        'fleet_type_id' => $fleetTypeIds[7], // Flatbed
        'first_driver' => 'Kamran Shah',
        'second_driver' => 'Yasir Mahmood',
        'name' => 'Isuzu Flatbed TK-7890',
        'registration_number' => 'TK-7890',
        'registration_city' => 'Multan',
        'ownership' => 'Leased',
        'diesel_opening_inventory' => 120.00,
        'created_by' => $adminUser->id,
      ],
    ];

    foreach ($fleets as $fleet) {
      Fleet::create($fleet);
    }
  }
}